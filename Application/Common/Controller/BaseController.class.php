<?php
// +--------------------------------------------------------------------------
// | ZAIYOUDAO [ 载攸道 先顺得常 ] <http://www.zaiyoudao.com>
// +--------------------------------------------------------------------------
// | Copyright © 2010-2015 ISDCE. All rights reserved <http://www.isdce.com>
// +--------------------------------------------------------------------------
// | Project: YiPHP [ 我会的仅仅是偷懒！ ] <http://www.yiphp.com>
// +--------------------------------------------------------------------------
// | Author: 牛很多戒很多不戒 <n@isdce.com> <http://juexue.wang>
// +--------------------------------------------------------------------------

namespace Common\Controller;

use Think\Controller;

/**
 * 开发者公共配置控制器
 */
class BaseController extends Controller
{

    //空操作默认为登陆
    public function _empty()
    {

        $url = $_SERVER['REQUEST_SCHEME'] . '://' . $_SERVER['HTTP_HOST'] . '/index.php/Auth/Login/Login/Login';
//				$url = $_SERVER['REQUEST_SCHEME'].'://'.$_SERVER['HTTP_HOST'].'/Simian/Auth/Public/Login.html';
        redirect("$url", 0, '');

    }




    /* 开发者公共配置 */
    protected function _initialize()
    {
        //判断是否登陆
        $m_user_auth = M('user_auth');
        $tcj_uid = $_COOKIE['tcj_uid'];
        $map['id'] = $tcj_uid;

        $authority = $m_user_auth->where($map)->field('user_auth,status,md5_key')->select();
        if ($_COOKIE['tcj_uid'] == null) {
            $url = $_SERVER['REQUEST_SCHEME'] . '://' . $_SERVER['HTTP_HOST'] . '/index.php/Auth/Login/Login/Login';
            $this->error('请登录', $url);
            die;
        }

        if ($_COOKIE['tcj_md5_key'] != $authority[0]['md5_key']) {
            $url = $_SERVER['REQUEST_SCHEME'] . '://' . $_SERVER['HTTP_HOST'] . '/index.php/Auth/Login/Login/Login';
            $this->error('您的账号已在其他设备登陆,请重新登录', $url);
            die;
        }
        if ($authority[0]['status'] == 0) {
            $url = $_SERVER['REQUEST_SCHEME'] . '://' . $_SERVER['HTTP_HOST'] . '/index.php/Auth/Login/Login/Login';
            $this->error('您的账号未通过审核，请联系管理员开通账户', $url);
            die;
        }

//			//权限判断
        $authority_initialize = $this->getAuthority();
        if ($authority_initialize == 0) {
            $url = $_SERVER['REQUEST_SCHEME'] . '://' . $_SERVER['HTTP_HOST'] . '/index.php/Auth/Login/Main/Main';
            $this->error('您没有权限进入该页面', $url);
            die;
        }

    }
    //用户信息
    public function getAuthority()
    {

        $m_user_auth = M('user_auth');
        $tcj_uid = $_COOKIE['tcj_uid'];
        $map['id'] = $tcj_uid;
        $data = $m_user_auth->where($map)->field('status')->find();
        return $data["status"];
    }

}

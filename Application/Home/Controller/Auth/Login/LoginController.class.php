<?php
namespace Home\Controller\Auth\Login;

use Think\Controller;

// 指定允许其他域名访问
header('Access-Control-Allow-Origin:*');
// 响应类型
header('Access-Control-Allow-Methods:POST');
// 响应头设置
header('Access-Control-Allow-Headers:x-requested-with,content-type');

class LoginController extends Controller
{
    //登陆行为
    public function Login()
    {
//        echo 1;

        if($_POST){
            $m_user_auth = M('user_auth');
            $map['user_name'] = $_POST['name'];
            $map['user_password'] = $_POST['password'];
            $list = $m_user_auth->where($map)->find();
            if($list["id"]){
                $result['status'] = 200;
                $result['message'] = "欢迎使用";
                cookie('tcj_uid', $list["id"], 3600 * 24 * 7);
                $now_time = date('Y-m-d H:i:s',time());
                $now = strtotime($now_time);
                $md5_key['md5_key'] = md5($list["id"].'tcj'.$now);
                cookie('tcj_md5_key',$md5_key['md5_key'], 3600 * 24 * 7);
                $map_save['id'] = $list["id"];
                $m_user_auth->where($map_save)->save($md5_key);

            }else{
                $result['status'] = 400;
                $result['message'] = "用户名或密码错误";
            }
            echo json_encode($result);

        }else{
            $this->theme('All')->display();
        }

    }
    //登陆行为
    public function Sign()
    {
//        echo 1;
        if($_POST){
            $m_user_auth = M('user_auth');
            $map['user_name'] = $_POST['name'];
            $list = $m_user_auth->where($map)->select();
            if($list){
                $result['status'] = 400;
                $result['message'] = "用户名重复";

            }else{
                $map_add['user_name'] = $_POST['name'];
                $map_add['user_password'] = $_POST['password'];
                $map_add['user_password'] = $_POST['user_nickname'];
                $re = $m_user_auth->add($map_add);
                if($re != ""){
                    $result['status'] = 200;
                    $result['message'] = "添加成功";

                    //设置相关cookie 1年有效期
                    cookie('tcj_uid', $re, 3600 * 24 * 7);
                    $now_time = date('Y-m-d H:i:s',time());
                    $now = strtotime($now_time);
                    $md5_key['md5_key'] = md5($re.'tcj'.$now);
                    cookie('tcj_md5_key',$md5_key['md5_key'], 3600 * 24 * 7);
                    $map_save['id'] = $re;
                    $m_user_auth->where($map_save)->save($md5_key);

                }else{
                    $result['status'] = 400;
                    $result['message'] = "添加失败";
                }
            }
            echo json_encode($result);

        }

    }

}

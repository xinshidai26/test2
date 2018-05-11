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

class BaseAPIController extends Controller {

    protected function _initialize()
    {
        // 指定允许其他域名访问
        header('Access-Control-Allow-Origin:*');
        // 响应类型
        header('Access-Control-Allow-Methods:POST');
        // 响应头设置
        header('Access-Control-Allow-Headers:x-requested-with,content-type');
    }


}

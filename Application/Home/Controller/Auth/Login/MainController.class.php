<?php
namespace Home\Controller\Auth\Login;

use Common\Controller\BaseController;

// 指定允许其他域名访问
header('Access-Control-Allow-Origin:*');
// 响应类型
header('Access-Control-Allow-Methods:POST');
// 响应头设置
header('Access-Control-Allow-Headers:x-requested-with,content-type');

class MainController extends BaseController
{
    //登陆行为
    public function Main()
    {
        $this->theme('All')->display();


    }


}

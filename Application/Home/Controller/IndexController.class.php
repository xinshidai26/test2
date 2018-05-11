<?php

namespace Home\Controller;
use Think\Controller;
use Think\Storage;

/**
 * 项目入口控制器
 */
class IndexController extends Controller {

    //项目入口跳转行为
    public function Index(){

//		if(!Storage::has('../Backup/install.lock')){
//
//			 $url = $_SERVER['REQUEST_SCHEME'].'://'.$_SERVER['HTTP_HOST'].'/install.php';
//
//			 redirect($url,0,'还没有安装了YiPHP，请安装');
//
//			 die;
//
//        }
        redirect(U('Auth/Login/Login/Login'),0,'');

    }


}
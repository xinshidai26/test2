<?php
return array(
	//'配置项'=>'配置值'


    //加载其他配置文件
    'LOAD_EXT_CONFIG' => 'db,authorization,email,domain',

    // 设置禁止访问的模块列表
    'MODULE_DENY_LIST'      =>  array('Common','Runtime'),

    // 设置可访问目录
    'MODULE_ALLOW_LIST'    =>    array('Home'),


    //默认访问目录
    'DEFAULT_MODULE'       =>    'Home',


    /* 模板相关配置 */
    'TMPL_PARSE_STRING' => array(

        '__BOOTSTRAP__'   => __ROOT__.'/Public/bootstrap',       //bootstrap文件
        '__All__'   => __ROOT__.'/Public/All',    //静态MEEZAO样式文件


    ),


    'URL_MODEL'             =>  2,       // URL访问模式,可选参数0、1、2、3,代表以下四种模式：
    // 0 (普通模式); 1 (PATHINFO 模式); 2 (REWRITE  模式); 3 (兼容模式)  默认为PATHINFO 模式
);
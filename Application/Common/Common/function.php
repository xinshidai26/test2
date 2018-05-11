<?php
/**
 * 公共函数库
 */

/**
 * 接受josn返回函数
 * @param string $url 请求的网址
 * @param integer|string|array $data 可发送过去的数据
 * @return integer|string|array 任何可能的返回数据
 */
//use \Org\Util\WechatApi;
//use \Org\Util\WechatOpenApi;
//use \Org\Util\WechatOpenApi;

use \Org\Util\AjaxPage;
use \think\Storage;

//require_once dirname(getcwd()) . '/php-emoji/lib/emoji.php';
//require_once dirname(getcwd()) . '/public_function/public_function.php';
//
//function redis_server()
//{
//    $redis = new Redis();
//    $redis->connect(C('REDIS_HOST'), C('REDIS_PORT'));
//    return $redis;
//}
//
//function sidekiq($job, $ary = array(), $queue = false)
//{
//    if (!$queue) {
//        $queue = C('DB_NAME');
//    }
//    $root = $_SERVER['DOCUMENT_ROOT'];
//
//    require_once dirname($root) . '/php-resques/demo/init.php';
//    date_default_timezone_set('Asia/Shanghai');
//
//    \Resque::setBackend('127.0.0.1:6379');
//    return (\Resque::enqueue($queue, $job, $ary));
//
//}


function sub_title($k)
{
//    echo($k);
//    $k[$k['en_name']] = ($k['data_sources']);

    $item = ['state', 'sort_number', 'created_at', 'updated_at'];
    foreach ($item as $i) {
        unset($k[$i]);
    }

    return $k;
}




function Ajax_Page($count, $limitRows = 15, $name = 'index', $map = '')
{
    $wei_xin = new AjaxPage($count, $limitRows, $name, $map);
    return $wei_xin;
}


/**
 * 删除整个目录
 * @param $dir
 * @return bool
 */
function delDir($dir, $ty = false)
{
//先删除目录下的所有文件：
    $dh = opendir($dir);
    while ($file = readdir($dh)) {
        if ($file != "." && $file != "..") {
            $fullpath = $dir . "/" . $file;
            if (!is_dir($fullpath)) {
                unlink($fullpath);
            } else {
                delDir($fullpath);
            }
        }
    }
    closedir($dh);
//删除当前文件夹：
    if ($ty == FALSE) {

        return rmdir($dir);
    }
}

function image_png_size_add($imgsrc, $imgdst, $max = 300)
{
    list($width, $height, $type) = getimagesize($imgsrc);

//根据最大值为300，算出另一个边的长度，得到缩放后的图片宽度和高度
    if ($width > $max || $height > $max) {


        if ($width > $height) {
            $new_width = $max;
            $new_height = $height * ($max / $width);
        } else {
            $new_height = $max;
            $new_width = $width * ($max / $height);
        }
    } else {
        $new_height = $height;
        $new_width = $width;
    }
    if ($type == 1) {
        copy($imgsrc, $imgdst);
    } else if ($type == 2) {
//        header('Content-Type:image/jpeg');
        $image_wp = imagecreatetruecolor($new_width, $new_height);
        $image = imagecreatefromjpeg($imgsrc);
        imagecopyresampled($image_wp, $image, 0, 0, 0, 0, $new_width, $new_height, $width, $height);
        imagejpeg($image_wp, $imgdst);
        imagedestroy($image_wp);
    } else if ($type == 3) {
//        header('Content-Type:image/png');
        $image_wp = imagecreatetruecolor($new_width, $new_height);
        $image = imagecreatefrompng($imgsrc);
        imagecopyresampled($image_wp, $image, 0, 0, 0, 0, $new_width, $new_height, $width, $height);
        imagepng($image_wp, $imgdst);
        imagedestroy($image_wp);
    }

    return true;
}

/**
 * desription 判断是否gif动画
 * @param sting $image_file图片路径
 * @return boolean t 是 f 否
 */
function check_gifcartoon($image_file)
{
    $fp = fopen($image_file, 'rb');
    $image_head = fread($fp, 1024);
    fclose($fp);
    return preg_match("/" . chr(0x21) . chr(0xff) . chr(0x0b) . 'NETSCAPE2.0' . "/", $image_head) ? false : true;
}


function https_request($url, $data = null)
{
    $curl = curl_init();
    curl_setopt($curl, CURLOPT_URL, $url);
    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, FALSE);
    curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, FALSE);
    if (!empty($data)) {
        curl_setopt($curl, CURLOPT_POST, 1);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
    }
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
    $output = curl_exec($curl);
    curl_close($curl);
    return $output;
}

/**
 * 生成文件夹
 * @author 牛很多戒很多不戒 <n@isdce.com> <http://juexue.wang>
 * @param string $dir 请求的网址
 * @param integer $mode 默认 0777 最高权限
 * @return fucntion 返回自身
 */
function mkdirs($dir, $mode = 0777)
{

    if (is_dir($dir) || @mkdir($dir, $mode)) return TRUE;
    if (!mkdirs(dirname($dir), $mode)) return FALSE;
    return @mkdir($dir, $mode);

}

/**
 * 获取树状类型
 * @author 牛很多戒很多不戒 <n@isdce.com> <http://juexue.wang>
 * @param array $ar 请求的网址
 * @param string $id 默认 id  当前对比的标识
 * @param string $pid 默认 pid 所属上级的标识
 * @return array 返回树状数组
 */
function find_child($ar, $id = 'id', $pid = 'pid')
{
    foreach ($ar as $v) $t[$v[$id]] = $v;

    foreach ($t as $k => $item) {
        if ($item[$pid]) {
            $t[$item[$pid]]['child'][$item[$id]] =& $t[$k];
            $t[$k]['reference'] = true;
        }
    }
    return $t;
}

/**
 * 递归去除树状重复类型
 * @author 牛很多戒很多不戒 <n@isdce.com> <http://juexue.wang>
 * @param array $list 请求的网址
 * @param string $p_id 所属上级的标识
 * @return array 返回树状数组
 */
function _findChildren($list, $p_id)
{
    //数据层级化，
    $r = array();
    foreach ($list as $id => $item) {
        if ($item['pid'] == $p_id) {
            $length = count($r);
            $r[$length] = $item;
            if ($t = _findChildren($list, $item['id'])) {
                $r[$length]['children'] = $t;
            }
        }
    }
    return $r;
}

/**
 * 清除顶级是隐藏的下级菜单 目前只考虑两级菜单 TODO无限极菜单的清楚
 * @author 牛很多戒很多不戒 <n@isdce.com> <http://juexue.wang>
 * @param array $menu 菜单组
 * @return array 返回菜单组
 */
function clean_menu($menu)
{

    $m_common_menu = M('common_menu');
    foreach ($menu as $i => $k) {

        if ($menu[$i]['pid'] != 0) {

            $map['id'] = $menu[$i]['pid'];
            $map['status'] = 1;
            $r = $m_common_menu->where($map)->find();
            if (!$r) {
                unset($menu[$i]);
            }

        }

    }

    return $menu;
}


function get_qr_code($data = "", $level = 'M', $size = '6')
{

    $url = "http://qrcode.zaiyoudao.com/open.php?data=$data&level=$level&size=$size";

    $img = file_get_contents($url);

    return $img;

}


/**
 * 获取ip所在地淘宝接口
 * @author 牛很多戒很多不戒 <n@isdce.com> <http://juexue.wang>
 * @param string $ip 查询ip
 * @return string 返回所在地
 */
function get_ip_location($ip)
{

    if (!$ip) {

        $ip = get_client_ip();

    }

    $api = "http://ip.taobao.com/service/getIpInfo.php?ip=$ip";

    $return_data = https_request($api);

    /**
     * 返回数据
     * @return integer $code 0为成功 1为失败
     * @return array $data 行为日志标识
     * @return string $data['country'] 国家
     * @return integer $data['country_id'] 国家id
     * @return string $data['area'] 地区
     * @return integer $data['area_id'] 地区id
     * @return string $data['region'] 省份
     * @return integer $data['region_id'] 省份id
     * @return string $data['city'] 城市
     * @return integer $data['city_id'] 城市id
     * @return string $data['county'] 区县
     * @return integer $data['county_id'] 区县id
     * @return string $data['isp'] 电信服务商
     * @return integer $data['isp_id'] 电信服务商id
     * @return string $data['ip'] 查询ip
     */
    $json_data = json_decode($return_data, true);

    if ($json_data['code'] == 0) {

        return $data = $json_data['data']['country'] . $json_data['data']['region'] . $json_data['data']['city'] . $json_data['data']['isp'];

    } else {

        return $data = '未知区域';

    }

}


/**
 * 行为日志函数
 * @author 牛很多戒很多不戒 <n@isdce.com> <http://juexue.wang>
 * @param string $action 行为日志标识
 */
function action_log($action = 'null')
{

    action_log_db();

    $batch = strtotime(date('Y-m', time()));

    //实例化公共行为日志数据库
    $table_name = "common_action_log_" . $batch;


    $m_common_action_log = M($table_name);

    //数据产生基本信息
    $data['time'] = time();
    $data['ip'] = get_client_ip(1);
    $data['sole'] = $action;
    $data['origin_url'] = $_SERVER['HTTP_REFERER'];

    //如果未知来源,或直接访问则为null
    if (!$data['origin_url']) {

        $data['origin_url'] = 'null';

    }

    $data['url'] = $_SERVER['REQUEST_SCHEME'] . '://' . $_SERVER['SERVER_NAME'] . $_SERVER['REQUEST_URI'];


    //从cookie读取三个基本参数
    $data['uid'] = $_COOKIE['uid'];
    if ($data['uid'] == null) {
        $data['uid'] = 0;
    }

    $data['user_group_id'] = C('USER_GROUP_ID');
    if ($data['user_group_id'] == null) {
        $data['user_group_id'] = 0;
    }

    $data['project_id'] = C('PROJECT_ID');
    if ($data['project_id'] == null) {
        $data['project_id'] = 0;
    }


    //获取并格式化九大数据
    $data['cookie'] = serialize($_COOKIE);
    $data['env'] = serialize($_ENV);
    $data['files'] = serialize($_FILES);
    $data['get'] = serialize($_GET);
    $data['post'] = serialize($_POST);
    $data['request'] = serialize($_REQUEST);
    $data['server'] = serialize($_SERVER);
    $data['session'] = serialize($_SESSION);
    $data['i'] = serialize(I());
    $data['retrospect'] = uniqid();

    $data['status'] = 1;

    $action_log_id = $m_common_action_log->add($data);

    cookie('action_log_id', $action_log_id, 3600 * 24 * 31);
}

/**
 * 行为日志函数
 * @author 牛很多戒很多不戒 <n@isdce.com> <http://juexue.wang>
 * @param string $action 行为日志标识
 */
function action_log_m($action = 'null')
{

    action_log_db();

    $batch = strtotime(date('Y-m', time()));

    //实例化公共行为日志数据库
    $table_name = "common_action_log_" . $batch;


    $m_common_action_log = M($table_name);

    //数据产生基本信息
    $data['time'] = time();
    $data['ip'] = get_client_ip(1);
    $data['sole'] = $action;
    $data['origin_url'] = $_SERVER['HTTP_REFERER'];

    //如果未知来源,或直接访问则为null
    if (!$data['origin_url']) {

        $data['origin_url'] = 'null';

    }

    $data['url'] = $_SERVER['REQUEST_SCHEME'] . '://' . $_SERVER['SERVER_NAME'] . $_SERVER['REQUEST_URI'];


    //从cookie读取三个基本参数
    $data['uid'] = $_COOKIE['mid'];
    if ($data['uid'] == null) {
        $data['uid'] = 0;
    }

    $data['user_group_id'] = C('USER_GROUP_ID');
    if ($data['user_group_id'] == null) {
        $data['user_group_id'] = 0;
    }

    $data['project_id'] = C('PROJECT_ID');
    if ($data['project_id'] == null) {
        $data['project_id'] = 0;
    }


    //获取并格式化九大数据
    $data['cookie'] = serialize($_COOKIE);
    $data['env'] = serialize($_ENV);
    $data['files'] = serialize($_FILES);
    $data['get'] = serialize($_GET);
    $data['post'] = serialize($_POST);
    $data['request'] = serialize($_REQUEST);
    $data['server'] = serialize($_SERVER);
    $data['session'] = serialize($_SESSION);
    $data['i'] = serialize(I());
    $data['retrospect'] = uniqid() . time() . rand(10032, 22423);

    $data['status'] = 1;

    $action_log_id = $m_common_action_log->add($data);

    cookie('action_log_id', $action_log_id, 3600 * 24 * 31);
}

//动态创建分表
function action_log_db()
{


    //创建新表
    $batch = strtotime(date('Y-m', time()));

    $map['db'] = $batch;
    $m_common_action_log_db = M('common_action_log_db');

    $result = $m_common_action_log_db->where($map)->find();

    if ($result) {

        return true;


    } else {

        $conn = mysql_connect(C('DB_HOST'), C('DB_USER'), C('DB_PWD'));
        $DB_name = C('DB_NAME');
        mysql_select_db($DB_name, $conn);
        $table_name = C('DB_PREFIX') . "common_action_log_" . $batch;

        $info = $m_common_action_log_db->order('id')->find();

        //$num = $info['num']+1;


        $query = "CREATE TABLE IF NOT EXISTS " . $table_name . "(
					  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '行为id',
					  `uid` int(11) NOT NULL COMMENT '用户UID',
					  `user_group_id` int(11) NOT NULL COMMENT '用户所属群组ID',
					  `project_id` int(11) NOT NULL COMMENT '用户所属项目ID',
					  `sole` varchar(50) NOT NULL COMMENT '用户行为标示',
					  `time` int(11) NOT NULL COMMENT '行为产生时间',
					  `ip` int(11) NOT NULL COMMENT '行为产生IP',
					  `origin_url` varchar(500) NOT NULL COMMENT '来源网址',
					  `url` varchar(500) NOT NULL COMMENT '行为产生URL',
					  `cookie` text NOT NULL COMMENT '用户Cookie',
					  `session` text NOT NULL COMMENT '用户Session',
					  `server` text NOT NULL COMMENT '用户Server',
					  `env` text NOT NULL COMMENT 'env数据',
					  `files` text NOT NULL COMMENT '上传文件数据',
					  `get` text NOT NULL COMMENT 'get数据',
					  `post` text NOT NULL COMMENT 'post数据',
					  `request` text NOT NULL COMMENT 'request数据',
					  `i` text NOT NULL COMMENT '用户提交数据',
					  `remain` float NOT NULL COMMENT '停留时间',
					  `retrospect` varchar(200) NOT NULL COMMENT '万能标识',
					  `status` int(11) NOT NULL COMMENT '状态',
					  PRIMARY KEY (`id`),
					  UNIQUE KEY `retrospect_2` (`retrospect`),
					  KEY `retrospect` (`retrospect`)
					) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='以批号查询的表' AUTO_INCREMENT=1";

        $result = mysql_query($query, $conn);

        if ($result) {


            $data['db'] = $batch;
            $data['time'] = time();
            $data['status'] = 1;

            $m_common_action_log_db->add($data);

            return true;

        } else {

            return false;

        }


    }


}

/**
 * 清除不看的行为日志
 * @author 牛很多戒很多不戒 <n@isdce.com> <http://juexue.wang>
 * @param string $data 序列化的数据
 * @return string 返回序列化的数据
 */
function clean_log($data)
{

    //暂时不看的数据
    $clean_data = unserialize($data);

    unset($clean_data['cookie']);
    unset($clean_data['session']);
    unset($clean_data['server']);
    unset($clean_data['message']);

    return serialize($clean_data);


}

/**
 * 写入文件函数
 * @author 牛很多戒很多不戒 <n@isdce.com> <http://juexue.wang>
 * @param string $source 文件目录
 * @param string $s 写入内容
 * @param string $iLine 起始
 * @param string $index 终止
 * @return array 返回写入信息
 */
function insertContent($source, $s, $iLine, $index)
{

    $file_handle = fopen($source, "r");

    $i = 0;

    $arr = array();

    while (!feof($file_handle)) {
        $line = fgets($file_handle);
        ++$i;
        if ($i == $iLine) {

            if ($index == strlen($line) - 1) {

                $arr[] = substr($line, 0, strlen($line) - 1) . $s . "n";

            } else {

                $arr[] = substr($line, 0, $index) . $s . substr($line, $index);

            }
        } else {

            $arr[] = $line;
        }
    }

    fclose($file_handle);

    return $arr;
}


/**
 * 获取文件夹大小
 * @author 牛很多戒很多不戒 <n@isdce.com> <http://juexue.wang>
 * @param string $dir 包含文件目录的文件
 * @return integer 返回文件大小
 */
function getDirSize($dir)
{
    $sizeResult = '';
    $handle = opendir($dir);
    while (false !== ($FolderOrFile = readdir($handle))) {
        if ($FolderOrFile != "." && $FolderOrFile != "..") {
            if (is_dir("$dir/$FolderOrFile")) {
                $sizeResult += getDirSize("$dir/$FolderOrFile");
            } else {
                $sizeResult += filesize("$dir/$FolderOrFile");
            }
        }
    }
    closedir($handle);
    return $sizeResult;
}

/**
 * 文件大小单位自动转换函数
 * @author 牛很多戒很多不戒 <n@isdce.com> <http://juexue.wang>
 * @param integer $size 文件大小
 * @return string 返回文件大小
 */
function getRealSize($size)
{

    $kb = 1024;         // Kilobyte
    $mb = 1024 * $kb;   // Megabyte
    $gb = 1024 * $mb;   // Gigabyte
    $tb = 1024 * $gb;   // Terabyte

    if ($size < $kb) {
        return $size . " B";
    } else if ($size < $mb) {
        return round($size / $kb, 2) . " KB";
    } else if ($size < $gb) {
        return round($size / $mb, 2) . " MB";
    } else if ($size < $tb) {
        return round($size / $gb, 2) . " GB";
    } else {
        return round($size / $tb, 2) . " TB";
    }
}


/**
 * 获得明年今天的时间戳
 * @author 牛很多戒很多不戒 <n@isdce.com> <http://juexue.wang>
 * @return integer 明年今天的时间戳
 */
function getNextYear()
{

    $time = time() + (365 * 24 * 60 * 60);
    return $time;
}

/**
 * 更新路由文件函数
 * @author 牛很多戒很多不戒 <n@isdce.com> <http://juexue.wang>
 */
function getNewRouteDomain()
{

    //备份路由时间
    $time = time();

    //模板文件
    $conf_file_path = BACKUP_PATH . 'Route/Domain/Public/domain.tpl';
    //新文件路径
    $new_conf_file_path = APP_PATH . 'Common/Conf/domain.php';


    //根据年月日设置目录
    $y = date('Y', time());
    $m = date('m', time());
    $d = date('d', time());

    $DataDir = BACKUP_PATH . "Route/Domain/$y/$m/$d/";
    $newfilename = 'domain_' . date('YmdHis', $time) . '_' . $time . '.php';

    mkdirs($DataDir);

    //创建备份文件路径
    $copy_conf_file_path = $DataDir . $newfilename;

    //实例化公共项目域名模型
    $m_common_route_domain = M('common_route_domain');

    $list = $m_common_route_domain->where('status=1')->order('id desc')->select();

    //备份原有解析
    copy($new_conf_file_path, $copy_conf_file_path);


    //开启路由配置
    $cc_data_before = "APP_SUB_DOMAIN_DEPLOY";
    $cc_data_middle = '=>';
    $cc_data_after = 1;

    $cc_app_sub_domain_deploy = "'" . $cc_data_before . "'" . $cc_data_middle . $cc_data_after . ",";

    //配置路由数组
    $cd_data_before = "APP_SUB_DOMAIN_RULES";
    $cd_data_middle = '=>';
    $cd_data_after = "array(";

    $cd_app_sub_domain_deploy = "'" . $cd_data_before . "'" . $cd_data_middle . $cd_data_after;

    //合成数组数据
    $cc_data = "<?php return array(" . $cc_app_sub_domain_deploy . $cd_app_sub_domain_deploy;


    $cc_arrInsert = insertContent($conf_file_path, $cc_data, 1, 0);


    //删除原文件
    unlink($new_conf_file_path);

    //循环写入新文件
    foreach ($cc_arrInsert as $value) {

        file_put_contents($new_conf_file_path, $value, FILE_APPEND);

    }


    foreach ($list as $i => $k) {

        //配置标准模型
        $data_before = $list[$i]['domain'];
        $data_middle = '=>';
        $data_after = $list[$i]['module'];

        $data = "'" . $data_before . "'" . $data_middle . "'" . $data_after . "',";

        $arrInsert = insertContent($new_conf_file_path, $data, 1, 500000);

        //删除原文件
        unlink($new_conf_file_path);

        //循环写入新文件
        foreach ($arrInsert as $value) {

            file_put_contents($new_conf_file_path, $value, FILE_APPEND);

        }


    }


    $cb_data = '),);';
    $cb_arrInsert = insertContent($new_conf_file_path, $cb_data, 1, 500001);

    //删除原文件
    unlink($new_conf_file_path);

    //循环写入新文件
    foreach ($cb_arrInsert as $value) {

        file_put_contents($new_conf_file_path, $value, FILE_APPEND);

    }


}

/**
 * 格式化字节大小
 * @author 牛很多戒很多不戒 <n@isdce.com> <http://juexue.wang>
 * @param  number $size 字节数
 * @param  string $delimiter 数字和单位分隔符
 * @return string            格式化后的带单位的大小
 */
function format_bytes($size, $delimiter = '')
{
    $units = array('B', 'KB', 'MB', 'GB', 'TB', 'PB');
    for ($i = 0; $size >= 1024 && $i < 5; $i++) $size /= 1024;
    return round($size, 2) . $delimiter . $units[$i];
}


/**
 * 处理方法
 * @author 牛很多戒很多不戒 <n@isdce.com> <http://juexue.wang>
 * @param  string $dirname 文件名
 */
function rmdirr($dirname)
{
    if (!file_exists($dirname)) {
        return false;
    }
    if (is_file($dirname) || is_link($dirname)) {
        return unlink($dirname);
    }
    $dir = dir($dirname);
    if ($dir) {
        while (false !== $entry = $dir->read()) {
            if ($entry == '.' || $entry == '..') {
                continue;
            }
            //递归
            rmdirr($dirname . DIRECTORY_SEPARATOR . $entry);
        }
    }
}

/**
 * 获取上一页
 * @return  string 上一页地址
 */
function backurl()
{
    $backurl = empty($_SERVER['HTTP_REFERER']) && strstr($_SERVER['HTTP_REFERER'],
        $_SERVER['HTTP_HOST']) ? '#' : $_SERVER['HTTP_REFERER'];
    return $backurl;
}

/**
 * 获取文件修改时间
 * @author 牛很多戒很多不戒 <n@isdce.com> <http://juexue.wang>
 * @param  string $file 文件名
 * @param  string $DataDir 文件目录地址
 */
function getfiletime($file, $DataDir)
{
    $a = filemtime($DataDir . $file);
    $time = date("Y-m-d H:i:s", $a);
    return $time;
}

/**
 * 获取文件的大小
 * @author 牛很多戒很多不戒 <n@isdce.com> <http://juexue.wang>
 * @param  string $file 文件名
 * @param  string $DataDir 文件目录地址
 * @retrun string $DataDir  返回文件大小
 */
function getfilesize($file, $DataDir)
{
    $perms = stat($DataDir . $file);
    $size = $perms['size'];
    // 单位自动转换函数
    $kb = 1024;         // Kilobyte
    $mb = 1024 * $kb;   // Megabyte
    $gb = 1024 * $mb;   // Gigabyte
    $tb = 1024 * $gb;   // Terabyte

    if ($size < $kb) {
        return $size . " B";
    } else if ($size < $mb) {
        return round($size / $kb, 2) . " KB";
    } else if ($size < $gb) {
        return round($size / $mb, 2) . " MB";
    } else if ($size < $tb) {
        return round($size / $gb, 2) . " GB";
    } else {
        return round($size / $tb, 2) . " TB";
    }
}


/**
 * 获取用户菜单权限
 * @author 牛很多戒很多不戒 <n@isdce.com> <http://juexue.wang>
 * @param  array $menus 判断用户菜单权限
 */
function getAuthMenu($menus)
{

    //如果超级管理员则不考虑
    if (getUID() == 1) {

        return true;
    }
    //生成菜单
    $m_common_menu = M('common_menu');

    $url = CONTROLLER_NAME . '/' . ACTION_NAME;
    foreach ($menus as $mi => $mk) {
        $auth_map['url'] = array('like', "$url%");
        $auth_map['id'] = $menus[$mi];

        $auth_data = $m_common_menu->where($auth_map)->find();

        if ($auth_data) {

            $auth_ok = 1;
            break;
        }

    }


    if ($auth_ok != 1) {

        if (IS_AJAX) {

            echo '没有权限';

        } else {

            redirect(U('Simian/Auth/Public/NoWay'), 0, '');
        }
        die;
    }


}

/**
 * 获取该用户是否有菜单权限
 * @author 牛很多戒很多不戒 <n@isdce.com> <http://juexue.wang>
 * @param  array $menus 判断用户菜单权限
 */
function getUserAuthMenu()
{

    //如果超级管理员则不考虑
    if (getUID() == 1) {

        return true;
    }
    //生成菜单
    $m_common_menu = M('common_menu');
    $m_common_authority_group_menu = M('common_authority_group_menu');

    $url = CONTROLLER_NAME . '/' . ACTION_NAME;

    $auth_map['url'] = array('like', "$url%");
    $auth_map['project_id'] = C('PROJECT_ID');


    $data = $m_common_menu->where($auth_map)->find();


    //判断用户菜单显示
    $mymenu = unserialize(getUserGroupMenu());


    foreach ($mymenu as $ii => $ik) {

        if ($data['id'] == $mymenu[$ii]) {

            $auth_ok = 1;
            break;
        }

    }


    if ($auth_ok != 1) {

        if (IS_AJAX) {

            echo '没有权限';

        } else {

            redirect(U('Simian/Auth/Public/NoWay'), 0, '');
        }

        die;
    }
}

/**
 * 获取当前用户组
 * @author 牛很多戒很多不戒 <n@isdce.com> <http://juexue.wang>
 * @param  array $menus 判断用户菜单权限
 */
function getUserGroup()
{

    $map['uid'] = $_COOKIE['uid'];
    $map['pid'] = C('PROJECT_ID');
    $map['status'] = 1;
    //生成菜单
    $m_common_user_relevance = M('common_user_relevance');

    $url = CONTROLLER_NAME . '/' . ACTION_NAME;

    $data = $m_common_user_relevance->where($map)->find();

    return $data['gid'];
}

/**
 * 获取当前用户组
 * @author 牛很多戒很多不戒 <n@isdce.com> <http://juexue.wang>
 * @param  array $menus 判断用户菜单权限
 */
function getUserGroupMenu()
{

    $map['uid'] = $_COOKIE['uid'];
    $map['pid'] = C('PROJECT_ID');
    $map['status'] = 1;
    //生成菜单
    $m_common_user_relevance = M('common_user_relevance');
    $m_common_authority_group_menu = M('common_authority_group_menu');


    $data = $m_common_user_relevance->where($map)->find();

    $mmap['gid'] = $data['gid'];
    $mmap['pid'] = C('PROJECT_ID');
    $mmap['status'] = 1;

    $mdata = $m_common_authority_group_menu->where($mmap)->find();

    return $mdata['value'];
}

/**
 * 获取用户顶级id
 * @author 牛很多戒很多不戒 <n@isdce.com> <http://juexue.wang>
 * @return  integer 返回菜单id
 */
function getTreeMenu()
{


    //生成菜单
    $m_common_menu = M('common_menu');

    $url = CONTROLLER_NAME . '/' . ACTION_NAME;

    $tree_map['url'] = array('like', "$url%");
    $tree_map['project_id'] = C('PROJECT_ID');
    $tree_map['status'] = 1;

    $tree_data = $m_common_menu->where($tree_map)->find();

    if ($tree_data['tier'] == 1) {

        return $tree_data['id'];

    } else {

        return getMenuTierOne($tree_data['pid']);

    }


}


/**
 * 获取用户当前菜单状态
 * @author 牛很多戒很多不戒 <n@isdce.com> <http://juexue.wang>
 * @return  integer 返回菜单id
 */
function getNowMenuID()
{


    //生成菜单
    $m_common_menu = M('common_menu');

    $url = CONTROLLER_NAME . '/' . ACTION_NAME;

    $map['url'] = array('like', "$url%");
    $map['project_id'] = C('PROJECT_ID');


    $data = $m_common_menu->where($map)->find();


    return $data['id'];


}

/**
 * 递归获取最上级菜单
 * @author 牛很多戒很多不戒 <n@isdce.com> <http://juexue.wang>
 * @param  integer $id 菜单id
 * @param  integer $num 递归开始深度
 * @return integer 返回顶级菜单id
 */
function getMenuTierOne($id, $begin_num = 0, $end_num = 5)
{

    //最多递归5层
    $begin_num++;
    if ($begin_num == $end_num) {
        return -1;
    }

    $m_common_menu = M('common_menu');
    $map['id'] = $id;
    $data = $m_common_menu->where($map)->find();

    if ($data['tier'] == 1) {

        return $data['id'];

    } else {

        return getMenuTierOne($data['pid'], $begin_num);
    }

}

/**
 * 获取用户UID
 * @author 牛很多戒很多不戒 <n@isdce.com> <http://juexue.wang>
 * @return integer 返回uid
 */
function getUID()
{

    return $_COOKIE['uid'];

}


/**
 * 获得指定天数及小时时间戳
 * @author 牛很多戒很多不戒 <n@isdce.com> <http://juexue.wang>
 * @param  integer $day 指定多少天之后
 * @param  integer $time 指定多少天之后的几个小时
 * @return integer 返回时间戳
 */
function getThatTime($day = 1, $time = 0)
{

    //基础时间
    $baseTime = date('Y-m-d', time());
    //基础三天
    $basetoday = strtotime($baseTime);
    $basetomorrow = $basetoday + (86400 * $day);

    $time = 3600 * $time;

    $data = $basetomorrow + $time;

    return $data;
}

;

/**
 * 获得今天时间戳
 * @author 牛很多戒很多不戒 <n@isdce.com> <http://juexue.wang>
 * @return integer 返回今天时间戳
 */
function getToday()
{

    //基础时间
    $baseTime = date('Y-m-d', time());
    //基础三天
    $basetoday = strtotime($baseTime);


    return $basetoday;
}

;

/**
 * 获得明天时间戳
 * @author 牛很多戒很多不戒 <n@isdce.com> <http://juexue.wang>
 * @return integer 获得明天时间戳
 */
function getTomorrow()
{

    //基础时间
    $baseTime = date('Y-m-d', time());
    //基础三天
    $basetoday = strtotime($baseTime);
    $basetomorrow = $basetoday + 86400;

    return $basetomorrow;
}

;

/**
 * 下载文件
 * @author 牛很多戒很多不戒 <n@isdce.com> <http://juexue.wang>
 * @param  string $file_name 文件名
 * @param  string $file_dir 文件目录
 */
function getFileDownload($file_name, $file_dir)
{

    if (!file_exists($file_dir . $file_name)) {

        //检查文件是否存在
        echo "文件找不到";
        exit;

    } else {

        $file = fopen($file_dir . $file_name, "r"); // 打开文件
        // 输入文件标签
        Header("Content-type: application/octet-stream");
        Header("Accept-Ranges: bytes");
        Header("Accept-Length: " . filesize($file_dir . $file_name)); //不考虑文件大小 否则会进入死循环
        Header("Content-Disposition: attachment; filename=" . $file_name);
        // 输出文件内容
        echo fread($file, filesize($file_dir . $file_name));
        fclose($file);


    }
}

/**
 * 获得增长比例
 * @author 牛很多戒很多不戒 <n@isdce.com> <http://juexue.wang>
 * @param  integer $nowdata 新数据
 * @param  integer $olddata 旧数据
 * @param  integer $digit 默认小数点后2位
 * @return integer 相对旧数据的增长比
 */
function getIncrease($nowdata, $olddata, $digit = 2)
{

    $increase = ($nowdata - $olddata) / $olddata;

    $increase = round($increase, $digit);
    return $increase;
}


/**
 * 获得剩余比
 * @author 牛很多戒很多不戒 <n@isdce.com> <http://juexue.wang>
 * @param  integer $nowdata 新数据
 * @param  integer $olddata 旧数据
 * @param  integer $digit 默认小数点后2位
 * @return integer 相对旧数据的增长比
 */
function getProportion($nowdata, $olddata, $digit = 2)
{

    $increase = $nowdata / $olddata;

    $increase = round($increase, $digit);

    return $increase;
}


/* 身份证处理相关 */


/**
 * 根据身份证号，自动返回对应的星座
 * @author 牛很多戒很多不戒 <n@isdce.com> <http://juexue.wang>
 * @param  string $cid 身份证号
 * @return string 返回星座
 */
function get_xingzuo($cid)
{
    $cid = getIDCard($cid);

    if (!isIdCard($cid)) return '';
    $bir = substr($cid, 10, 4);
    $month = (int)substr($bir, 0, 2);
    $day = (int)substr($bir, 2);
    $strValue = '';
    if (($month == 1 && $day >= 20) || ($month == 2 && $day <= 18)) {
        $strValue = "水瓶座";
    } else if (($month == 2 && $day >= 19) || ($month == 3 && $day <= 20)) {
        $strValue = "双鱼座";
    } else if (($month == 3 && $day > 20) || ($month == 4 && $day <= 19)) {
        $strValue = "白羊座";
    } else if (($month == 4 && $day >= 20) || ($month == 5 && $day <= 20)) {
        $strValue = "金牛座";
    } else if (($month == 5 && $day >= 21) || ($month == 6 && $day <= 21)) {
        $strValue = "双子座";
    } else if (($month == 6 && $day > 21) || ($month == 7 && $day <= 22)) {
        $strValue = "巨蟹座";
    } else if (($month == 7 && $day > 22) || ($month == 8 && $day <= 22)) {
        $strValue = "狮子座";
    } else if (($month == 8 && $day >= 23) || ($month == 9 && $day <= 22)) {
        $strValue = "处女座";
    } else if (($month == 9 && $day >= 23) || ($month == 10 && $day <= 23)) {
        $strValue = "天秤座";
    } else if (($month == 10 && $day > 23) || ($month == 11 && $day <= 22)) {
        $strValue = "天蝎座";
    } else if (($month == 11 && $day > 22) || ($month == 12 && $day <= 21)) {
        $strValue = "射手座";
    } else if (($month == 12 && $day > 21) || ($month == 1 && $day <= 19)) {
        $strValue = "魔羯座";
    }
    return $strValue;

}

//用php从身份证中提取生日,包括15位和18位身份证
function getIDCardInfo($IDCard)
{
    $result['error'] = 0;//0：未知错误，1：身份证格式错误，2：无错误
    $result['flag'] = '';//0标示成年，1标示未成年
    $result['tdate'] = '';//生日，格式如：2012-11-15
    if (!eregi("^[1-9]([0-9a-zA-Z]{17}|[0-9a-zA-Z]{14})$", $IDCard)) {
        $result['error'] = 1;
        return $result;
    } else {
        if (strlen($IDCard) == 18) {
            $tyear = intval(substr($IDCard, 6, 4));
            $tmonth = intval(substr($IDCard, 10, 2));
            $tday = intval(substr($IDCard, 12, 2));
            if ($tyear > date("Y") || $tyear < (date("Y") - 100)) {
                $flag = 0;
            } elseif ($tmonth < 0 || $tmonth > 12) {
                $flag = 0;
            } elseif ($tday < 0 || $tday > 31) {
                $flag = 0;
            } else {
                $tdate = $tyear . "-" . $tmonth . "-" . $tday . " 00:00:00";
                if ((time() - mktime(0, 0, 0, $tmonth, $tday, $tyear)) > 18 * 365 * 24 * 60 * 60) {
                    $flag = 0;
                } else {
                    $flag = 1;
                }
            }
        } elseif (strlen($IDCard) == 15) {
            $tyear = intval("19" . substr($IDCard, 6, 2));
            $tmonth = intval(substr($IDCard, 8, 2));
            $tday = intval(substr($IDCard, 10, 2));
            if ($tyear > date("Y") || $tyear < (date("Y") - 100)) {
                $flag = 0;
            } elseif ($tmonth < 0 || $tmonth > 12) {
                $flag = 0;
            } elseif ($tday < 0 || $tday > 31) {
                $flag = 0;
            } else {
                $tdate = $tyear . "-" . $tmonth . "-" . $tday . " 00:00:00";
                if ((time() - mktime(0, 0, 0, $tmonth, $tday, $tyear)) > 18 * 365 * 24 * 60 * 60) {
                    $flag = 0;
                } else {
                    $flag = 1;
                }
            }
        }
    }
    $result['error'] = 2;//0：未知错误，1：身份证格式错误，2：无错误
    $result['isAdult'] = $flag;//0标示成年，1标示未成年
    $result['birthday'] = $tdate;//生日日期
    return $result;
}

/**
 * 根据身份证号，自动返回对应的生肖
 * @author 牛很多戒很多不戒 <n@isdce.com> <http://juexue.wang>
 * @param  string $cid 身份证号
 * @return string 返回生肖
 */
function get_shengxiao($cid)
{
    $cid = getIDCard($cid);
    if (!isIdCard($cid)) return '';
    $start = 1901;
    $end = $end = (int)substr($cid, 6, 4);
    $x = ($start - $end) % 12;
    $value = "";
    if ($x == 1 || $x == -11) {
        $value = "鼠";
    }
    if ($x == 0) {
        $value = "牛";
    }
    if ($x == 11 || $x == -1) {
        $value = "虎";
    }
    if ($x == 10 || $x == -2) {
        $value = "兔";
    }
    if ($x == 9 || $x == -3) {
        $value = "龙";
    }
    if ($x == 8 || $x == -4) {
        $value = "蛇";
    }
    if ($x == 7 || $x == -5) {
        $value = "马";
    }
    if ($x == 6 || $x == -6) {
        $value = "羊";
    }
    if ($x == 5 || $x == -7) {
        $value = "猴";
    }
    if ($x == 4 || $x == -8) {
        $value = "鸡";
    }
    if ($x == 3 || $x == -9) {
        $value = "狗";
    }
    if ($x == 2 || $x == -10) {
        $value = "猪";
    }
    return $value;
}

/**
 * 根据身份证号，获得年级
 * @author 牛很多戒很多不戒 <n@isdce.com> <http://juexue.wang>
 * @param  string $cid 身份证号
 * @return string 返回年级
 */
function get_school($cid)
{
    $cid = getIDCard($cid);
    if (!isIdCard($cid)) return '';

    //获取月份
    $month = (int)substr($cid, 10, 2);
    //获取年份
    $year = (int)substr($cid, 6, 4);
    //排除生日
    $nowyear = (int)date(Y);
    //获取根据年份的年龄
    $age = $nowyear - $year;
    //根据8月31日判断
    if ($month >= 8) {

        $value = $age - 6;
        if ($value == 0) {

            $value = 1;
        }

    } else {

        $value = $age - 5;
    }

    return $value;
}

/**
 * 根据身份证号，获得年级
 * @author 牛很多戒很多不戒 <n@isdce.com> <http://juexue.wang>
 * @param  string $cid 身份证号
 * @param  integer $stature 升高
 * @param  integer $weight 体重
 * @return integer 返回BMI值
 */
function get_bmi($cid, $stature, $weight)
{
    $cid = getIDCard($cid);
    if (!isIdCard($cid)) return '';

    //获取月份
    $month = (int)substr($cid, 10, 2);
    //获取年份
    $year = (int)substr($cid, 6, 4);
    //排除生日
    $nowyear = (int)date(Y);
    //获取根据年份的年龄
    $age = $nowyear - $year;
    //根据8月31日判断
    if ($month >= 8) {

        $school = $age - 7;


    } else {

        $school = $age - 6;
    }

    $sex = get_xingbie($cid, 2);
    $bmi = round($weight / (($stature * $stature) / 10000), 1);
    return $bmi;
}

/**
 * 根据BMI值获得相应数据
 * @author 牛很多戒很多不戒 <n@isdce.com> <http://juexue.wang>
 * @param  integer $bmi BMI值
 * @return string 返回状态
 */
function get_bmi_info($bmi)
{

    if ($bmi < 14.5) {

        $value = '过于瘦';

    } else if ($bmi > 14.5 and $bmi < 16) {

        $value = '偏瘦';

    } else if ($bmi > 16 and $bmi < 18.5) {

        $value = '正常';

    } else if ($bmi > 18.5 and $bmi < 20.5) {

        $value = '偏胖';

    } else if ($bmi > 20.5) {

        $value = '肥胖';
    }

    return $value;
}

/**
 * 根据身份证号，自动返回对应的生肖
 * @author 牛很多戒很多不戒 <n@isdce.com> <http://juexue.wang>
 * @param  string $cid 身份证号
 * @return string 返回属相
 */
function get_garde($cid)
{
    $cid = getIDCard($cid);
    if (!isIdCard($cid)) return '';
    $start = 1901;
    $end = $end = (int)substr($cid, 6, 4);
    $x = ($start - $end) % 12;
    $value = "";
    if ($x == 1 || $x == -11) {
        $value = "鼠";
    }
    if ($x == 0) {
        $value = "牛";
    }
    if ($x == 11 || $x == -1) {
        $value = "虎";
    }
    if ($x == 10 || $x == -2) {
        $value = "兔";
    }
    if ($x == 9 || $x == -3) {
        $value = "龙";
    }
    if ($x == 8 || $x == -4) {
        $value = "蛇";
    }
    if ($x == 7 || $x == -5) {
        $value = "马";
    }
    if ($x == 6 || $x == -6) {
        $value = "羊";
    }
    if ($x == 5 || $x == -7) {
        $value = "猴";
    }
    if ($x == 4 || $x == -8) {
        $value = "鸡";
    }
    if ($x == 3 || $x == -9) {
        $value = "狗";
    }
    if ($x == 2 || $x == -10) {
        $value = "猪";
    }
    return $value;
}

/**
 * 根据身份证号，自动返回性别
 * @author 牛很多戒很多不戒 <n@isdce.com> <http://juexue.wang>
 * @param  integer $cid 身份证号
 * @param  integer $comm 返回类型
 * @return string|integer 返回性别
 */
function get_xingbie($cid, $comm = 0)
{
    $cid = getIDCard($cid);
    if (!isIdCard($cid)) return '';
    $sexint = (int)substr($cid, 16, 1);
    if ($comm == 1) {
        return $sexint % 2 === 0 ? '女士' : '先生';
    } else if ($comm == null) {
        return $sexint % 2 === 0 ? '女' : '男';
    } else if ($comm == 2) {
        return $sexint % 2 === 0 ? '0' : '1';
    }

}

function get_xingbie_n($cid, $comm = 0)
{
    $cid = getIDCard($cid);
    dump($cid);
    if (!isIdCard($cid)) return 0;
    $sexint = (int)substr($cid, 16, 1);
//    if ($comm == 1) {
    return $sexint % 2 === 0 ? 2 : 1;
//    } else if ($comm == null) {
//        return $sexint % 2 === 0 ? '女' : '男';
//    } else if ($comm == 2) {
//        return $sexint % 2 === 0 ? '0' : '1';
//    }

}

/**
 * 根据身份证号，获得年纪
 * @author 牛很多戒很多不戒 <n@isdce.com> <http://juexue.wang>
 * @param  integer $id 身份证号
 * @return integer 返回年纪
 */
function getAgeByID($id)
{

    //过了这年的生日才算多了1周岁
    if (empty($id)) return '';
    $date = strtotime(substr($id, 6, 8));
    //获得出生年月日的时间戳
    $today = strtotime('today');
    //获得今日的时间戳
    $diff = floor(($today - $date) / 86400 / 365);
    //得到两个日期相差的大体年数

    //strtotime加上这个年数后得到那日的时间戳后与今日的时间戳相比
    $age = strtotime(substr($id, 6, 8) . ' +' . $diff . 'years') > $today ? ($diff + 1) : $diff;

    return $age;
}

/**
 * 检查是否是身份证号
 * @author 牛很多戒很多不戒 <n@isdce.com> <http://juexue.wang>
 * @param  integer $number 身份证号
 */
function isIdCard($number)
{
    $number = getIDCard($number);
    // 转化为大写，如出现x
    $number = strtoupper($number);
    //加权因子
    $wi = array(7, 9, 10, 5, 8, 4, 2, 1, 6, 3, 7, 9, 10, 5, 8, 4, 2);
    //校验码串
    $ai = array('1', '0', 'X', '9', '8', '7', '6', '5', '4', '3', '2');
    //按顺序循环处理前17位
    $sigma = 0;
    for ($i = 0; $i < 17; $i++) {
        //提取前17位的其中一位，并将变量类型转为实数
        $b = (int)$number{$i};

        //提取相应的加权因子
        $w = $wi[$i];

        //把从身份证号码中提取的一位数字和加权因子相乘，并累加
        $sigma += $b * $w;
    }
    //计算序号
    $snumber = $sigma % 11;

    //按照序号从校验码串中提取相应的字符。
    $check_number = $ai[$snumber];

    if ($number{17} == $check_number) {
        return true;
    } else {
        return false;
    }
}

/**
 * 把15位身份证转换成18位
 * @author 牛很多戒很多不戒 <n@isdce.com> <http://juexue.wang>
 * @param  integer $number 身份证号
 * @return string  返回身份证号
 */
function getIDCard($idCard)
{
    // 若是15位，则转换成18位；否则直接返回ID
    if (15 == strlen($idCard)) {
        $W = array(7, 9, 10, 5, 8, 4, 2, 1, 6, 3, 7, 9, 10, 5, 8, 4, 2, 1);
        $A = array("1", "0", "X", "9", "8", "7", "6", "5", "4", "3", "2");
        $s = 0;
        $idCard18 = substr($idCard, 0, 6) . "19" . substr($idCard, 6);
        $idCard18_len = strlen($idCard18);
        for ($i = 0; $i < $idCard18_len; $i++) {
            $s = $s + substr($idCard18, $i, 1) * $W [$i];
        }
        $idCard18 .= $A [$s % 11];
        return $idCard18;
    } else {
        return $idCard;
    }
}

function load_exce($inputFileName, $refile, $data_source_id)
{
    $data_source_id = (int)$data_source_id;
    $program = "python  $refile  -f  $inputFileName  --opt $data_source_id";
    exec($program, $output, $return_var);
    $c = (json_decode($output[0])->data);
    $dd = array_chunk($c, 30000);
    return $dd;
}
//excel读取
function excel_reads($info, $data_source_id, &$total_number)
{
    $total_number = 0;
    $dir = dirname(getcwd()) . "/Website/Uploads/" . $info['file']['savepath'];
    $refile = dirname(getcwd()) . '/analysis/readxlsx.py';
    $filename = $dir . $info['file']['savename'];
    if (Storage::has($filename) && Storage::has($refile)) {
        try {
            $data = load_exce($filename, $refile, $data_source_id);
            $table = D('intermediate_records');
            $table->startTrans();
            foreach ($data as $item) {
                $s = implode(',', $item);
                $total_number += count($item);
                $sql = 'insert into zyd_intermediate_records ( phone_number,name,updated_at,created_at,data_source_id,is_activated) VALUES ' . $s . ' ;';
                $table->execute($sql);
            }
            $table->commit();
        }    //捕获异常
        catch (Exception $e) {
            echo 'Message: ' . $e->getMessage() . '=======';
        }
    }
}


/**
 * 判断是否是手机号
 * 修改周凯：
 * 修改内容：增加17手机号内容，
 * @author 牛很多戒很多不戒 <n@isdce.com> <http://juexue.wang>
 * @param  integer $tel 手机号
 */
function isTel($tel)
{

    if (preg_match("/^13[0-9]{1}[0-9]{8}$|15[0-9]{1}[0-9]{8}$|18[0-9]{1}[0-9]{8}$|17[0-9]{1}[0-9]{8}$/", $tel)) {
        return true;

    } else {
        return false;
    }
}

/**
 * 加密函数
 * @author 牛很多戒很多不戒 <n@isdce.com> <http://juexue.wang>
 * @param   $data 加密内容
 * @param   $key  加密钥匙
 * @param  string 返回加密内容
 */
function encrypt($data, $key)
{
    $char = '';
    $str = "";
    $key = md5($key);
    $x = 0;
    $len = strlen($data);
    $l = strlen($key);
    for ($i = 0; $i < $len; $i++) {
        if ($x == $l) {
            $x = 0;
        }
        $char .= $key{$x};
        $x++;
    }
    for ($i = 0; $i < $len; $i++) {
        $str .= chr(ord($data{$i}) + (ord($char{$i})) % 256);
    }
    return base64_encode($str);
}

/**
 * 解密函数
 * @author 牛很多戒很多不戒 <n@isdce.com> <http://juexue.wang>
 * @param   $data 解密内容
 * @param   $key  解密钥匙
 * @param  string 返回加密内容
 */
function decrypt($data, $key)
{
    $char = '';
    $str = "";
    $key = md5($key);
    $x = 0;
    $data = base64_decode($data);
    $len = strlen($data);
    $l = strlen($key);
    for ($i = 0; $i < $len; $i++) {
        if ($x == $l) {
            $x = 0;
        }
        $char .= substr($key, $x, 1);
        $x++;
    }
    for ($i = 0; $i < $len; $i++) {
        if (ord(substr($data, $i, 1)) < ord(substr($char, $i, 1))) {
            $str .= chr((ord(substr($data, $i, 1)) + 256) - ord(substr($char, $i, 1)));
        } else {
            $str .= chr(ord(substr($data, $i, 1)) - ord(substr($char, $i, 1)));
        }
    }
    return $str;
}


/**
 * 判断是否是邮箱
 * @author 牛很多戒很多不戒 <n@isdce.com> <http://juexue.wang>
 * @param   $email 邮箱
 * @param   $test_mx  true
 * @param
 */
function is_valid_email($email, $test_mx = false)
{

    if (eregi("^([_a-z0-9-]+)(\.[_a-z0-9-]+)*@([a-z0-9-]+)(\.[a-z0-9-]+)*(\.[a-z]{2,4})$", $email)) {

        if ($test_mx) {

            list($username, $domain) = split("@", $email);

            return getmxrr($domain, $mxrecords);

        } else {

            return true;
        }


    } else {

        return false;

    }


}


//获取商品函数
function getGoodsInfo($id)
{

    //商品表
    $m_sssmall_goods = M('sssmall_goods');

    $map['id'] = $id;
    //商品基本信息
    $data = $m_sssmall_goods->where($map)->find();


    //获得品牌信息
    $brand = getBrandInfo($data['brand']);

    $data['brand_title'] = $brand['title']; //品类信息
    $data['storey_title'] = $brand['storey_title']; //楼层信息


    //获得品类信息
    $category = getCategoryInfo($data['category']);

    $data['category_title'] = $category['title']; //品类信息


    return $data;


}

//获得楼层信息
function getStoreyInfo($id)
{

    //关联获取品牌信息
    $m_smartcms_nav = M('smartcms_nav'); //pid=25 status=1 为正常的楼层信息sort排序 越大越靠前

    $map['id'] = $id;

    $data = $m_smartcms_nav->where($map)->find();

    return $data;

}

//获得品牌信息
function getBrandInfo($id)
{

    //关联获取品牌信息
    $m_smartcms_article = M('smartcms_article'); //cid=30 status=1 为正常的品牌信息sort排序 越大越靠前

    $map['id'] = $id;

    $data = $m_smartcms_article->where($map)->find();

    //通过品牌获取楼层信息
    $storey = getStoreyInfo($data['storey']);

    $data['storey_title'] = $storey['title'];

    return $data;

}


//获得品牌信息
function getAllBrand()
{

    //关联获取品牌信息
    $m_smartcms_article = M('smartcms_article'); //cid=30 status=1 为正常的品牌信息sort排序 越大越靠前

    $map['cid'] = 30;
    $map['status'] = 1;

    $list = $m_smartcms_article->where($map)->order('sort desc')->select();

    return $list;

}

//获得品类信息
function getCategoryInfo($id)
{

    //关联获取品类信息
    $m_smartcms_nav = M('smartcms_nav'); //pid=32 status=1 为正常的品牌信息sort排序 越大越靠前

    $map['id'] = $id;

    $data = $m_smartcms_nav->where($map)->find();

    return $data;


}

//获得品类信息
function getIndexInfo($id)
{

    //关联获取品类信息
    $m_smartcms_nav = M('smartcms_nav'); //pid=32 status=1 为正常的品牌信息sort排序 越大越靠前

    $map['id'] = $id;

    $data = $m_smartcms_nav->where($map)->find();

    return $data;


}

//获得品类信息
function getBCInfo($id)
{

    //关联获取品类信息
    $m_meezao_brand_category = M('meezao_brand_category'); //pid=32 status=1 为正常的品牌信息sort排序 越大越靠前

    $map['id'] = $id;

    $data = $m_meezao_brand_category->where($map)->find();

    return $data;


}

/*'js_behavior_record_id'       '记录id'),
                'content_id'    '详情id(互动ID)'),
                'action_id'     '行为类型id(互动类型ID)



 * 浏览行为
               浏览（一级行为） 卡券列表（二级行为）                               卡券ID            卡券，（3级行为）
js_behavior_records(2,        18,     ,array(  'content_id'=>$card_id, 'action_id'=>18));

   * 微信行为
 微信（一级行为） 关注（二级行为）
js_behavior_records(12,        33     );
 微信（一级行为） 取消关注（二级行为）
js_behavior_records(12,        34     );


 * 领取行为
               领取（一级行为） 卡券列表（二级行为）    谁                            卡券ID                 卡券，（3级行为）
js_behavior_records(4,       20,    ,array(   'content_id'=>$card_id, 'action_id'=>20 ));

 * 核销行为
               核销（一级行为） 卡券列表（二级行为）    谁                            卡券ID                 卡券，（3级行为）
js_behavior_records(6,       24,    ,array(   'content_id'=>$card_id, 'action_id'=>24 ));

array('brand_id'=> 28,'level'=>在字典表中的level的4中查找id);


*/
//js行为记录
function js_behavior_records($openid, $behavior_active, $behavior_column = '', $active = false)
{
    if ($openid) {
        $wx_user_info_id = M('weixin_user_info')->where(array('openid' => $openid))->getField('id');
        if ($wx_user_info_id) {
            $muta_id = M('multi_user_type_associates')->where(array('wx_user_info_id' => $wx_user_info_id))->getField('id');
            if ($muta_id) cookie('muta_id', $muta_id, 3600);
        } else {
            list($muta_id, $mid) = add_muntail($openid);
            if ($muta_id) {
                cookie('muta_id', $muta_id, 3600 * 24 * 7);
                cookie('mid', $mid, 3600 * 24 * 7);
            }
//            if ($muta_id) cookie('muta_id', $muta_id, 3600);
        }

        if ($muta_id) {
            //  js行为记录
            $js_behavior_records = M('js_behavior_records');
            $f = format_time();
            $map_br = array(
                'behavior_active' => $behavior_active,
                'behavior_column' => $behavior_column,
                'multi_user_type_associate_id' => $muta_id,
                'created_at' => $f,
                'updated_at' => $f
            );

            $js_behavior_record_id = $js_behavior_records->add($map_br);
            //    如果某些值不为空则说明需要进行子表的写入操作,否则不执行
            if ($active) {
                $active = MergeArray($active,array('action_id'=>$behavior_active));
                record_sub_table_behavior($active, $js_behavior_record_id, $js_behavior_records);
            }
        }
    }
}

//  记录子表行为
function record_sub_table_behavior($active, $js_behavior_record_id, $js_behavior_records)
{
    if (is_array($active) && count($active) > 0) {
        $table_name = M('detailed_behavior');

        $map_sub_table = array(
            'js_behavior_record_id' => $js_behavior_record_id,
            'created_at' => format_time(),
            'updated_at' => format_time()
        );
        $js_behavior_record_table_id = $table_name->add(array_merge($map_sub_table, $active));
        if($js_behavior_record_table_id){
            $data = array('detailed_behavior_id' => $js_behavior_record_table_id);
            $js_behavior_records->where('id=' . $js_behavior_record_id)->save($data);
        }
    };
}

//js行为记录
function js_behavior_record($f, $openid, $behavior_active, $behavior_column = '', $active = false)
{
    if ($openid) {
        $wx_user_info_id = M('weixin_user_info')->where(array('openid' => $openid))->getField('id');
        if ($wx_user_info_id) {
            $muta_id = M('multi_user_type_associates')->where(array('wx_user_info_id' => $wx_user_info_id))->getField('id');
        } else {
            list($muta_id, $mid) = add_muntail($openid);
        }

        if ($muta_id) {
            //  js行为记录
            $js_behavior_records = M('js_behavior_records');
            $map_br = array(
                'behavior_active' => $behavior_active,
                'behavior_column' => $behavior_column,
                'multi_user_type_associate_id' => $muta_id,
                'created_at' => $f,
                'updated_at' => $f
            );

            $js_behavior_record_id = $js_behavior_records->add($map_br);
            //    如果某些值不为空则说明需要进行子表的写入操作,否则不执行
            if ($active) record_sub_table_behaviors($f, $active, $js_behavior_record_id, $js_behavior_records);
        }
    }
}

//  记录子表行为
function record_sub_table_behaviors($f, $active, $js_behavior_record_id, $js_behavior_records)
{
    if (is_array($active) && count($active) > 0) {
        $table_name = M('detailed_behavior');

        $map_sub_table = array(
            'js_behavior_record_id' => $js_behavior_record_id,
            'created_at' => $f,
            'updated_at' => $f
        );
        $js_behavior_record_table_id = $table_name->add(array_merge($map_sub_table, $active));
        $data = array('detailed_behavior_id' => $js_behavior_record_table_id);
        $js_behavior_records->where('id=' . $js_behavior_record_id)->save($data);
    };
}

//  行为字典表
function behavior_dictionary($behavior_active)
{
    $behavior_dictionaries = M('behavior_dictionaries');
    $map = array('level' => 1, 'id' => $behavior_active);
    $table_name = $behavior_dictionaries->where($map)->getField('t_name');
    return $table_name;

}

//获得品类信息
function getNowURL()
{

    //关联获取品类信息
    $url = $_SERVER['REQUEST_SCHEME'] . '://' . $_SERVER['HTTP_HOST'];

    return $url;


}

function getMeeZaoURL()
{

    $common_route_domain = M('common_route_domain');

    $data = $common_route_domain->where('id=6')->find();
    return $data['domain'];


}


/**********************
 * @file - path to zip file
 * @destination - destination directory for unzipped files
 */
function unzip_file($file, $destination)
{
    // create object
    $zip = new ZipArchive();
    // open archive
    if ($zip->open($file) !== TRUE) {
        die ('Could not open archive');
    }
    // extract contents to destination directory
    $zip->extractTo($destination);
    // close archive
    $zip->close();
    //echo 'Archive extracted to directory';
}


function getNull($data)
{

    if ($data == null) {

        $data = ' ';

    }

    return $data;
}

//获取微信相关数据
/*new_focus	新关注人数
cancel_attention	取消关注人数
number_net	净增关注人数
cumulative_number	累积关注人数
reading_time	阅读加总人数
reading_rate	阅读率
*/

function getWeixin_Data($Token, $begin_date)
{
    //获取用户增减数据
//	$url = 'https://api.weixin.qq.com/datacube/getusersummary?access_token='.$Token;
    //$yesterday = date("Y-m-d",strtotime("-1 day"));
    //$yesterday2 = date("Y-m-d",strtotime("-2 day"));
    $yesterday = $begin_date;
    $v = 0;
    $nu = 10;
    for ($i = 0; $i < $nu; $i++) {
        $res1 = getUserData( $yesterday);
        if ($res1 == null) {

            $v++;
        } else {
            break;
        }
    }
    $j = 0;
    for ($i = 0; $i < $nu; $i++) {
        $res2 = getUserChangeData( $yesterday);
        if ($res2 == null) {
            $j++;
        } else {
            break;
        }
    }

    $k = 0;
    for ($i = 0; $i < $nu; $i++) {
        $res3 = getImageData( $yesterday);
        if ($res3 == null) {
            $k++;

        } else {
            break;
        }
    }
    dump('v==' . $v);
    dump('j==' . $j);

    dump('k==' . $k);

    $arr['new_focus'] = $res1['new_focus'];
    $arr['cancel_attention'] = $res1['cancel_attention'];
    $arr['number_net'] = $res1['number_net'];
    $arr['cumulative_number'] = $res2['cumulative_number'];
    $arr['reading_time'] = $res3['reading_time'];
    $arr['reading_rate'] = substr($res3['reading_time'] / $res2['cumulative_number'] * 100, 0, 6);

//	dump($arr);
    return ($arr);

}


function http($url, $params)
{
    $opts = array(
        CURLOPT_TIMEOUT => 30,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_SSL_VERIFYPEER => false,
        CURLOPT_SSL_VERIFYHOST => false,
        CURLOPT_HEADER => false,
        CURLOPT_URL => $url,
        CURLOPT_POST => 1,
        CURLOPT_POSTFIELDS => $params
    );
    dump($params);
    /* 根据请求类型设置特定参数 */
//	switch(strtoupper($method)){
//		case 'GET':
//			$opts[CURLOPT_URL] = $url .'?'. http_build_query($params);
//			break;
//		case 'POST':

//			break;
//	}
//	if ($ssl) {
//		$pemPath = dirname(__FILE__).'/Wechat/';
//		$pemCret = $pemPath.$this->pem.'_cert.pem';
//		$pemKey  = $pemPath.$this->pem.'_key.pem';
//		if (!file_exists($pemCret)) {
//			$this->error = '证书不存在';
//			return false;
//		}
//		if (!file_exists($pemKey)) {
//			$this->error = '密钥不存在';
//			return false;
//		}
//		$opts[CURLOPT_SSLCERTTYPE] = 'PEM';
//		$opts[CURLOPT_SSLCERT]     = $pemCret;
//		$opts[CURLOPT_SSLKEYTYPE]  = 'PEM';
//		$opts[CURLOPT_SSLKEY]      = $pemKey;
//	}
    /* 初始化并执行curl请求 */
    $ch = curl_init();
    curl_setopt_array($ch, $opts);
    $data = curl_exec($ch);
    $err = curl_errno($ch);
    $errmsg = curl_error($ch);
    curl_close($ch);
    if ($err > 0) {
        $this->error = $errmsg;
        return false;
    } else {
        dump(json_decode($data));
        return (json_decode($data));
    }
}


function getWeixinData($Token)
{
//	$Token,
    $yesterday = date("Y-m-d", strtotime("-1 day"));
    $res1 = getUserData( $yesterday);
    if ($res1 == null) {
        $res1 = getUserData( $yesterday);
        if ($res1 == null) {
            $res1 = getUserData( $yesterday);
            if ($res1 == null) {
                $res1 = getUserData( $yesterday);
                goto a;
            }
        }
    }
    a:
    $res2 = getUserChangeData( $yesterday);
    if ($res2 == null) {
        $res2 = getUserChangeData( $yesterday);
        if ($res2 == null) {
            $res2 = getUserChangeData( $yesterday);
            if ($res2 == null) {
                $res2 = getUserChangeData( $yesterday);
                goto b;
            }
        }
    }
    b:
    $res3 = getImageData( $yesterday);
    if ($res3 == null) {
        $res3 = getImageData( $yesterday);
        if ($res3 == null) {
            $res3 = getImageData( $yesterday);
            if ($res3 == null) {
                $res3 = getImageData( $yesterday);
                goto c;
            }
        }
    }
    c:
    $arr['new_focus'] = $res1['new_focus'];
    $arr['cancel_attention'] = $res1['cancel_attention'];
    $arr['number_net'] = $res1['number_net'];
    $arr['cumulative_number'] = $res2['cumulative_number'];
    $arr['reading_time'] = $res3['reading_time'];
    $arr['reading_rate'] = substr($res3['reading_time'] / $res2['cumulative_number'] * 100, 0, 6);

//	dump($arr);
    return ($arr);


}

//curl_post函数
function curl_post($url, $Data)
{
    //初始化
    $ch = curl_init();
    //设置参数
    //在局域网内访问https站点时需要设置以下两项！
    //此两项正式上线时需要更改（不检查和验证认证）
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);

    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $Data);
    curl_setopt($ch, CURLOPT_HEADER, 0);
    //采集
    $output = curl_exec($ch);
    if (curl_errno($ch)) {
        // dump(curl_error($ch));
    }
    //关闭
    curl_close($ch);
//    dump(json_decode($output));

    $res = json_decode($output);
    return ($res);
}

function get_Token()
{
    return get_tokens();
}

function get_token_new($res)
{
    if ($res->errcode == 40001) {
        get_Token();
//        return(null);
    }
}

//curl_get函数
function curl_get($url)
{
    //初始化
    $ch = curl_init();
    //设置参数
    //在局域网内访问https站点时需要设置以下两项！
    //此两项正式上线时需要更改（不检查和验证认证）
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);

    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    //采集
    $output = curl_exec($ch);
    if (curl_errno($ch)) {
        dump(curl_error($ch));
    }
    //关闭
    curl_close($ch);
    $res = json_decode($output);
    return ($res);
}

function getUserData( $yesterday)
{

    $list = wei_xin_api()->getDatacube('user', 'summary', $yesterday);

    //dump($list);
    if ($list == null) {
//        get_token_new($res);
        return (null);
    } else {
        $arr['new_focus'] = 0;
        $arr['cancel_attention'] = 0;
        foreach ($list as $key => $value) {
            $arr['new_focus'] += $value->new_user;
            $arr['cancel_attention'] += $value->cancel_user;

        }
        $arr['number_net'] = $arr['new_focus'] - $arr['cancel_attention'];
        //dump($arr);
        return ($arr);
    }

}

function getUserChangeData( $yesterday)
{
    $list = wei_xin_api()->getDatacube('user', 'cumulate', $yesterday);
    $arr = array();
    if ($list == null) {
        return (null);
    } else {
        foreach ($list as $key => $value) {
            $arr['cumulative_number'] = $value['cumulate_user'];
        }
        return ($arr);
    }
}

function get_total_msg($list, $yesterday)
{
    $total_infos = M('total_info');
    $total_info = $total_infos->where('ref_date = "' . $yesterday . '"')->getField('id', true);

//        $ti = $total_info->select();
    if ($total_info) {
        $total_info = implode(',', $total_info);
        $total_infos->delete($total_info);
    }
    $msg['state'] = false;

    if ($list) {
//        $list = $res->list;
        if ($list) {
//            $list = (array)$list;
            $values = [];
            foreach ($list as $key => $value) {
                $value = (array)$value;
                $dt = date('Y-m-d H:i:s');
                $value['created_at'] = $dt;
                $value['updated_at'] = $dt;
                array_push($values, $value);
            }
            $total_infos->addAll($values);

            $msg['state'] = true;
            $msg['msg']['errcode'] = 0;
            $msg['msg']['errmsg'] = 'total_info 更新完成';

        } else {
            $msg['msg'] = (array)$list;
        }
    } else {
        $msg['msg']['errcode'] = 400;
        $msg['msg']['errmsg'] = 'total_info 更新失败';
    }
    return $msg;
}

function get_send_place_cnname($en_name)
{
    $cn_name = $en_name;
    if ($en_name == 'NewProductRecommend') {
        $cn_name = '新品推荐';
    } else if ($en_name == 'LatestInformation') {
        $cn_name = '最新资讯';
    } else if ($en_name == 'ThemeActivity') {
        $cn_name = '主题活动';
    } else if ($en_name == 'GroupIssued') {
        $cn_name = '微信群发';
    }
    return $cn_name;
}

function get_send_place_ty($ty)
{
    $cn_name = $ty;
    if ($ty == 1) {
        $cn_name = '成功';
    } else if ($ty == 2) {
        $cn_name = '失败';
    } else if ($ty == 0) {
        $cn_name = '已撤回';
    }
    return $cn_name;
}

//获取图文统计数据
function datacube_getuserread( $yesterday)
{
    $msg['state'] = false;
    $list = wei_xin_api()->getDatacube('article', 'read', $yesterday);

//  int_page_read_user  图文页（点击群发图文卡片进入的页面）的阅读人数
    if ($list == null) {
//        get_token_new($res);
        $msg['msg'] = (array)$list;
    } else {
        $msg = get_total_msg($list, $yesterday);
    }
    return ($msg);
}

function get_date_array($day = 180)
{

    $days = array();
    for ($i = $day; $i > 0; $i--) {
        $ds = strtotime("-" . $i . ' day');
        $ds = date("Y-m-d", $ds);
        $days[] = $ds;
    }
    return ($days);
}

/**
 * @param $start_year 表示从开始的年
 * @param int $ty = 1; 表示当前时间的前一天
 * @param int $start_day = 1; 表示从该年的第一天开始计数
 * @param int $end_day = 100;//为了显示，以100天为例
 */
function get_data_array($start_year, $ty = 1, $start_day = 2, $end_day = 300)
{
    $date_array = array();
    $d = date("Y-m-d", strtotime("-" . $ty . " day"));
    for ($day = $start_day; $day <= $end_day; $day++) {
        $temp_date = date("Y-m-d", mktime(0, 0, 0, 1, $day, $start_year));//这个是将从2010-1-1开始的80天，依次存入数组
        array_push($date_array, $temp_date);
        if ($temp_date == $d) {
            break;
        }
    }
    return $date_array;
}

/*
     *功能: 得到wx用户数据统计
     *参数： day ,日期 ,实例：2017-07-07， 备注：不能大于当前日期
     *
     */
function get_increase_wx_users_data($yesterday = false)
{
    if ($yesterday == FALSE) {
        $d = strtotime("yesterday");
        $yesterday = date("Y-m-d", $d);
    }

    $day = $yesterday;
    $get_data_array = $day;
    $day = (strtotime($day));
    $days = (strtotime(date("Y-m-d")));
    if ($day && $day < $days) {
        $wx = wei_xin_api();
        $get_data_array = array($get_data_array);
        $dt = format_time();
        $total_infos = M('increase_wx_users');
        $total_infos->where(array('ref_date' => array('in', $get_data_array)))->delete();
        $date = array();
        $dates = array();
        foreach ($get_data_array as $data) {
            $value = array();
            $user_summary = $wx->getDatacube('user', 'summary', $data);
            $cumulate_user = $wx->getDatacube('user', 'cumulate', $data)[0]['cumulate_user'];
            if ($cumulate_user) {
                $value['ref_date'] = $data;
                $value['cumulate_user'] = $cumulate_user;
                $value['user_source'] = 0;
                $value['new_user'] = 0;
                $value['cancel_user'] = 0;
                $value['created_at'] = $dt;
                $value['updated_at'] = $dt;
                if ($user_summary) {
                    foreach ($user_summary as $key => $v) {
                        $date[] = (MergeArray($value, $v));
                    }
                } else {
                    $dates[] = $value;
                }
            }
        }
        //        dump($date);
        if ($date) $total_infos->addAll($date);
        if ($dates) $total_infos->addAll($dates);
    }

}

//获取图文群发总数据
//url:https://api.weixin.qq.com/datacube/getarticletotal?access_token=ACCESS_TOKEN
function datacube_getarticletotal($yesterday = false)
{
    $dats = array();
    if ($yesterday != FALSE) {
        $dats = get_total_imgtext_gather_date($yesterday);;
    } else {
        for ($i = -7; $i < 0; $i++) {
            $yesterday = date("Y-m-d", strtotime($i . " day"));
            $msg = get_total_imgtext_gather_date($yesterday);
            array_push($dats, $msg);
        }
    }
    return ($dats);
}

/**
 * 时间差计算
 *
 * @param Timestamp $time
 * @return String Time Elapsed
 * @author Shelley Shyan
 * @copyright http://phparch.cn (Professional PHP Architecture)
 */
function time2Units($time)
{
    $year = floor($time / 60 / 60 / 24 / 365);
    $time -= $year * 60 * 60 * 24 * 365;
    $month = floor($time / 60 / 60 / 24 / 30);
    $time -= $month * 60 * 60 * 24 * 30;
    $week = floor($time / 60 / 60 / 24 / 7);
    $time -= $week * 60 * 60 * 24 * 7;
    $day = floor($time / 60 / 60 / 24);
    $time -= $day * 60 * 60 * 24;
    $hour = floor($time / 60 / 60);
    $time -= $hour * 60 * 60;
    $minute = floor($time / 60);
    $time -= $minute * 60;
    $second = $time;
    $elapse = '';

    $unitArr = array('年' => 'year', '个月' => 'month', '周' => 'week', '天' => 'day',
        '小时' => 'hour', '分钟' => 'minute', '秒' => 'second'
    );

    foreach ($unitArr as $cn => $u) {
        if ($$u > 0) {
            $elapse = $$u . $cn;
            break;
        }
    }

    return $elapse;
}

function get_time_string($begin_time, $end_time)
{
    $times = timediff($begin_time, $end_time);
    $res = '';

    if ($times['day']) {
        $res .= $times['day'] . '天';
    }
    if ($times['hour']) {
        $res .= $times['hour'] . '小时';
    }
    if ($times['min']) {
        $res .= $times['min'] . '分';
    }
    if ($times['sec']) {
        $res .= $times['sec'] . '秒';
    }
    return $res;
}

function timediff($begin_time, $end_time)
{
    if ($begin_time < $end_time) {
        $starttime = $begin_time;
        $endtime = $end_time;
    } else {
        $starttime = $end_time;
        $endtime = $begin_time;
    }
    $timediff = $endtime - $starttime;
    $days = intval($timediff / 86400);
    $remain = $timediff % 86400;
    $hours = intval($remain / 3600);
    $remain = $remain % 3600;
    $mins = intval($remain / 60);
    $secs = $remain % 60;
    $res = array("day" => $days, "hour" => $hours, "min" => $mins, "sec" => $secs);
    return $res;
}

function get_total_imgtext_gather_date($yesterday)
{

    $wx = wei_xin_api();
    $list = $wx->getDatacube('article', 'total', $yesterday);
    $msg['msg']['errcode'] = $wx->errCode;
    $msg['msg']['errmsg'] = $wx->errMsg;
    if ($list == null) {
    } else {
        $msg = get_total_imgtext_gather($list, $yesterday);
    }
    return $msg;
}

//获取图文分享转发数据
//https://api.weixin.qq.com/datacube/getusershare?access_token=ACCESS_TOKEN
function datacube_getusershare($Token, $yesterday)
{
    $list = wei_xin_api()->getDatacube('article', 'share', $yesterday);
    return ($list);
}

//获取用户增减数据
//https://api.weixin.qq.com/datacube/getusersummary?access_token=ACCESS_TOKEN
/**
 * @param $Token
 * @param bool|false $yesterday
 * @return array
 */
function datacube_getusersummary($yesterday = false)
{
    $list = wei_xin_api()->getDatacube('user','summary',$yesterday);
    $msg = get_getusersummary_data($list, $yesterday);
    return ($msg);
}

function get_getusersummary_data($res, $yesterday)
{
    $total_infos = M('increase_wx_users');
    $total_info = $total_infos->where('ref_date = "' . $yesterday . '"')->getField('id', true);

//        $ti = $total_info->select();
    if ($total_info) {
//        $total_info = implode(',', $total_info);
        $map['id'] = array('in', $total_info);
        $total_infos->where($map)->delete();
    }
    $msg['state'] = false;
    if ($res) {
        $list = $res->list;
        if ($list) {
            $list = (array)$list;
            $values = [];
            $dt = format_time();

            foreach ($list as $key => $value) {
                $value = (array)$value;
                $value['created_at'] = $dt;
                $value['updated_at'] = $dt;
                array_push($values, $value);
            }
            $total_infos->addAll($values);

            $msg['state'] = true;
            $msg['msg']['errcode'] = 0;
            $msg['msg']['errmsg'] = 'increase_wx_users更新完成';
        } else {
            $msg['msg'] = (array)$res;
        }
    } else {
        $msg['msg']['errcode'] = 400;
        $msg['msg']['errmsg'] = 'increase_wx_users更新失败';
    }
    return $msg;
}


function get_total_imgtext($list, $yesterday)
{

    $total_infos = M('image_text_info');
    $total_info = $total_infos->where('ref_date = "' . $yesterday . '"')->getField('id', true);
    if ($total_info) {
        $total_info = implode(',', $total_info);
        $total_infos->delete($total_info);
    }
    $msg['state'] = false;
    if ($list) {
        if ($list) {
            $values = [];
            foreach ($list as $key => $value) {
                $value = (array)$value;
                $dt = date('Y-m-d H:i:s');
                $value['created_at'] = $dt;
                $value['updated_at'] = $dt;
                array_push($values, $value);
            }
            $total_infos->addAll($values);
            $msg['state'] = true;
            $msg['msg']['errcode'] = 0;
            $msg['msg']['errmsg'] = 'image_text_info更新完成';
        } else {
            $msg['msg'] = $list;
        }
    } else {
        $msg['msg']['errcode'] = 400;
        $msg['msg']['errmsg'] = 'image_text_info更新失败';
    }
    return $msg;
}

//获取图文群发总数据
function get_total_imgtext_gather($res, $yesterday)
{
    $total_infos = M('image_text_gather');
    $total_info = $total_infos->where('ref_date = "' . $yesterday . '"')->getField('id', true);
    if ($total_info) {
        $total_info = implode(',', $total_info);
        $total_infos->delete($total_info);
    }

    $msg['state'] = false;
    if ($res) {
        $arrays = [];
        foreach ($res as $key => $value) {
            $dt = format_time();
            $valu['ref_date'] = $value['ref_date'];
            $valu['msgid'] = $value['msgid'];
            $valu['title'] = $value['title'];
            $valu['created_at'] = $dt;
            $valu['updated_at'] = $dt;
            $details = $value['details'];
            foreach ($details as $b) {
                $arrays[] = MergeArray($valu, $b);
            }
        }
        if ($arrays) {
            $total_infos->addAll($arrays);
            $msg['state'] = true;
            $msg['msg']['errcode'] = 0;
            $msg['msg']['errmsg'] = 'image_text_gather 更新完成';
        } else {
            $msg['state'] = false;
            $msg['msg']['errcode'] = -1;
            $msg['msg']['errmsg'] = 'image_text_gather 失败';
        }


    } else {
        $msg['msg']['errcode'] = 400;
        $msg['msg']['errmsg'] = 'image_text_gather 更新失败';
    }
    return $msg;
}

//获取图文群发每日数据
//https://api.weixin.qq.com/datacube/getarticlesummary?access_token=ACCESS_TOKEN
function datacube_getarticlesummary($Token, $yesterday)
{

    $list = wei_xin_api()->getDatacube('article', 'summary', $yesterday);

    $msg['state'] = false;
    if ($list == null) {
//        get_token_new($res);
        $msg['msg'] = $list;
    } else {
        $msg = get_total_imgtext($list, $yesterday);
    }
    return ($msg);

}


function getImageData( $yesterday)
{

    $list = wei_xin_api()->getDatacube('article','read',$yesterday);
    //dump($list);
    if ($list == null) {
//        get_token_new($res);
        return (null);
    } else {
        $arr['reading_time'] = 0;
        foreach ($list as $key => $value) {
            $arr['reading_time'] += $value['int_page_read_user'];
        }
        $arr['reading_rate'] = $arr['reading_time'] / $arr['cumulative_number'] * 100;
        $arr['reading_rate'] = substr($arr['reading_rate'], 0, 6);
        //dump($arr);
        return ($arr);
    }
}

function get_more_url()
{
    $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' || $_SERVER['SERVER_PORT'] == 443) ? "https://" : "http://";
    $url = "$protocol$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
    return $url;
}


//微信文案自定义方法
function request_post($url, $data)
{
    //初始化cURL方法
    $ch = curl_init();
    //设置cURL参数
    $opts = array(
        //在局域网内访问https站点时需要设置以下两项，关闭ssl验证！
        //此两项正式上线时需要更改（不检查和验证认证）
        CURLOPT_SSL_VERIFYPEER => false,
        CURLOPT_SSL_VERIFYHOST => false,
        CURLOPT_TIMEOUT => 30,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_URL => $url,
        CURLOPT_POST => true,
        CURLOPT_POSTFIELDS => $data
    );
    curl_setopt_array($ch, $opts);
    //执行cURL操作
    $output = curl_exec($ch);
    if (curl_errno($ch)) {    //cURL操作发生错误处理。
        var_dump(curl_error($ch));
        die;
    }
    //关闭cURL
    curl_close($ch);
    $res = json_decode($output);
    return ($res);   //返回json数据
}

/*
     * 微信公众号接口调用函数(通过是否传入data判断其为get请求还是post请求)
     */
function WeChat_request($url, $data = null)
{
    //初始化cURL方法
    $ch = curl_init();
    //设置cURL参数（基本参数）
    $opts = array(
        //在局域网内访问https站点时需要设置以下两项，关闭ssl验证！
        //此两项正式上线时需要更改（不检查和验证认证）
        CURLOPT_SSL_VERIFYPEER => false,
        CURLOPT_SSL_VERIFYHOST => false,
        CURLOPT_TIMEOUT => 30,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_URL => $url,
        /*CURLOPT_POST           => true,
        CURLOPT_POSTFIELDS     => $data*/
    );
    curl_setopt_array($ch, $opts);
    //post请求参数
    if (!empty($data)) {
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
    }
    //执行cURL操作
    $output = curl_exec($ch);
    if (curl_errno($ch)) {    //cURL操作发生错误处理。
        var_dump(curl_error($ch));
        die;
    }
    //关闭cURL
    curl_close($ch);
    $res = json_decode($output);
    return ($res);   //返回json数据
}

function WeChat_request2($url, $data = null)
{
    //初始化cURL方法
    $ch = curl_init();
    //设置cURL参数（基本参数）
    $opts = array(
        //在局域网内访问https站点时需要设置以下两项，关闭ssl验证！
        //此两项正式上线时需要更改（不检查和验证认证）
        CURLOPT_SSL_VERIFYPEER => false,
        CURLOPT_SSL_VERIFYHOST => false,
        CURLOPT_TIMEOUT => 30,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_URL => $url,
        /*CURLOPT_POST           => true,
        CURLOPT_POSTFIELDS     => $data*/
    );
    curl_setopt_array($ch, $opts);
    //post请求参数
    if (!empty($data)) {
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
    }
    //执行cURL操作
    $output = curl_exec($ch);
    if (curl_errno($ch)) {    //cURL操作发生错误处理。
        var_dump(curl_error($ch));
        die;
    }
    //关闭cURL
    curl_close($ch);
//    $res = json_decode($output);
    return ($output);   //返回json数据
}

function resultProcess($res)
{
    if (!empty($res->errcode)) {
        return (errorMsg($res->errcode));
    } else {
        return $res;
    }
}

function errorMsg($errcode)
{
    $msg = array(-1 => "系统繁忙",
        0 => "请求成功",
        4001 => "指定文案不存在",
        40001 => "获取access_token时AppSecret错误，或者access_token无效",
        40002 => "不合法的凭证类型",
        40003 => "不合法的OpenID",
        40004 => "不合法的媒体文件类型",
        40005 => "不合法的文件类型",
        40006 => "不合法的文件大小",
        40007 => "不合法的媒体文件id",
        40008 => "不合法的消息类型",
        40009 => "不合法的图片文件大小,图片大小为0或者超过1M",
        40010 => "不合法的语音文件大小",
        40011 => "不合法的视频文件大小",
        40012 => "不合法的缩略图文件大小",
        40013 => "不合法的APPID",
        40014 => "不合法的access_token",
        40015 => "不合法的菜单类型",
        40016 => "不合法的按钮个数",
        40017 => "不合法的按钮个数",
        40018 => "不合法的按钮名字长度",
        40019 => "不合法的按钮KEY长度",
        40020 => "不合法的按钮URL长度",
        40021 => "不合法的菜单版本号",
        40022 => "不合法的子菜单级数",
        40023 => "不合法的子菜单按钮个数",
        40024 => "不合法的子菜单按钮类型",
        40025 => "不合法的子菜单按钮名字长度",
        40026 => "不合法的子菜单按钮KEY长度",
        40027 => "不合法的子菜单按钮URL长度",
        40028 => "不合法的自定义菜单使用用户",
        40029 => "不合法的oauth_code",
        40030 => "不合法的refresh_token",
        40031 => "不合法的openid列表",
        40032 => "不合法的openid列表长度",
        40033 => "不合法的请求字符，不能包含xxxx格式的字符",
        40035 => "不合法的参数",
        40038 => "不合法的请求格式",
        40039 => "不合法的URL长度",
        40050 => "不合法的分组id",
        40051 => "分组名字不合法",
        40053 => "不合法的actioninfo，请开发者确认参数正确。",
        40054 => "不合法的自定义菜单url",
        40056 => "不合法的Code码",
        40059 => "不合法的消息ID",
        45030 => "该cardid无接口权限",
        45033 => "用户领取次数超过限制get_limit",
        45031 => "库存为0",
        40071 => "不合法的卡券类型",
        40116 => "不合法的Code码",
        40117 => "微信号不合法",
        41011 => "缺少必填字段",
        45021 => "字段超过长度限制，请参考相应接口的字段说明",
        40072 => "不合法的编码方式",
        40078 => "不合法的卡券状态",
        40079 => "不合法的时间",
        40080 => "不合法的CardExt",
        40099 => "卡券已被核销",
        40100 => "不合法的时间区间",
        40132 => "微信号不合法",
        40137 => "不支持的图片格式",
        41012 => "缺少cardid参数",
        40127 => "卡券被用户删除或转赠中",
        40122 => "不合法的库存数量",
        41001 => "缺少access_token参数",
        41002 => "缺少appid参数",
        41003 => "缺少refresh_token参数",
        41004 => "缺少secret参数",
        41005 => "缺少多媒体文件数据",
        41006 => "缺少media_id参数",
        41007 => "缺少子菜单数据",
        41008 => "缺少oauth code",
        41009 => "缺少openid",
        42001 => "access_token超时",
        42002 => "refresh_token超时",
        42003 => "oauth_code超时",
        43001 => "需要GET请求",
        43002 => "需要POST请求",
        43003 => "需要HTTPS请求",
        43004 => "需要接收者关注",
        43005 => "需要好友关系",
        43009 => "自定义SN权限，请前往公众平台申请",
        43010 => "无储值权限，请前往公众平台申请",
        44001 => "多媒体文件为空",
        44002 => "POST的数据包为空",
        44003 => "图文消息内容为空",
        44004 => "文本消息内容为空",
        45001 => "多媒体文件大小超过限制",
        45002 => "消息内容超过限制",
        45003 => "标题字段超过限制",
        45004 => "描述字段超过限制",
        45005 => "链接字段超过限制",
        45006 => "图片链接字段超过限制",
        45007 => "语音播放时间超过限制",
        45008 => "图文消息超过限制",
        45009 => "接口调用超过限制",
        45010 => "创建菜单个数超过限制",
        45015 => "回复时间超过限制",
        45016 => "系统分组，不允许修改",
        45017 => "分组名字过长",
        45018 => "分组数量超过上限",
        45022 => "应用名字长度不合法，合法长度为2-16个字",
        45024 => "账号数量超过上限",
        45025 => "同一个成员每周只能邀请一次",
        45026 => "触发删除用户数的保护",
        45027 => "mpnews每天只能发送100次",
        45028 => "超过上限",
        45029 => "media_id对该应用不可见",
        46001 => "不存在媒体数据",
        46002 => "不存在的菜单版本",
        46003 => "不存在的菜单数据",
        46004 => "不存在的用户",
        47001 => "解析JSON/XML内容错误",
        48001 => "api功能未授权",
        48003 => "群发功能未开启",
        50001 => "用户未授权该api",
        50002 => "用户受限，可能是违规后接口被封禁",
        61451 => "参数错误(invalid parameter)",
        61452 => "无效客服账号(invalid kf_account)",
        61453 => "客服帐号已存在(kf_account exsited)",
        61454 => "客服帐号名长度超过限制(仅允许10个英文字符，不包括@及@后的公众号的微信号)(invalid kf_acount length)",
        61455 => "客服帐号名包含非法字符(仅允许英文+数字)(illegal character in kf_account)",
        61456 => "客服帐号个数超过限制(10个客服账号)(kf_account count exceeded)",
        61457 => "无效头像文件类型(invalid file type)",
        61450 => "系统错误(system error)",
        61500 => "日期格式错误",
        61501 => "日期范围错误",
        65104 => "门店的类型不合法，必须严格按照附表的分类填写",
        65105 => "图片url 不合法，必须使用接口1 的图片上传接口所获取的url",
        65106 => "门店状态必须未审核通过",
        65107 => "扩展字段为不允许修改的状态",
        65109 => "门店名为空",
        65110 => "门店所在详细街道地址为空",
        65111 => "门店的电话为空",
        65112 => "门店所在的城市为空",
        65113 => "门店所在的省份为空",
        65114 => "图片列表为空",
        65115 => "poi_id 不正确",
        7000000 => '请求正常,无语义结果',
        7000001 => '缺失请求参数',
        7000002 => 'signature 参数无效',
        7000003 => '地理位置相关配置 1 无效',
        7000004 => '地理位置相关配置 2 无效',
        7000005 => '请求地理位置信息失败',
        7000006 => '地理位置结果解析失败',
        7000007 => '内部初始化失败',
        7000008 => '非法 appid(获取密钥失败)',
        7000009 => '请求语义服务失败',
        7000010 => '非法 post 请求',
        7000011 => 'post 请求 json 字段无效',
        7000030 => '查询 query 太短',
        7000031 => '查询 query 太长',
        7000032 => '城市、经纬度信息缺失',
        7000033 => 'query 请求语义处理失败',
        7000034 => '获取天气信息失败',
        7000035 => '获取股票信息失败',
        7000036 => 'utf8 编码转换失败',
        9001001 => "POST数据参数不合法",
        9001002 => "远端服务不可用",
        9001003 => "Ticket不合法",
        9001004 => "获取摇周边用户信息失败",
        9001005 => "获取商户信息失败",
        9001006 => "获取OpenID失败",
        9001007 => "上传文件缺失",
        9001008 => "上传素材的文件类型不合法",
        9001009 => "上传素材的文件尺寸不合法",
        9001010 => "上传失败",
        9001020 => "帐号不合法",
        9001021 => "已有设备激活率低于50%，不能新增设备",
        9001022 => "设备申请数不合法，必须为大于0的数字",
        9001023 => "已存在审核中的设备ID申请",
        9001024 => "一次查询设备ID数量不能超过50",
        9001025 => "设备ID不合法",
        9001026 => "页面ID不合法",
        9001027 => "页面参数不合法",
        9001028 => "一次删除页面ID数量不能超过10",
        9001029 => "页面已应用在设备中，请先解除应用关系再删除",
        9001030 => "一次查询页面ID数量不能超过50",
        9001031 => "时间区间不合法",
        9001032 => "保存设备与页面的绑定关系参数错误",
        9001033 => "门店ID不合法",
        9001034 => "设备备注信息过长",
        9001035 => "设备申请参数不合法",
        9001036 => "查询起始值begin不合法");
    return $msg[$errcode] ? $msg[$errcode] : $errcode;
}

//微信获取接口调用的access_token
//获取api_access_token
function getApiToken()
{
    return getToken();
}


/**
 * 生成二维码
 * @author  zk
 * @param   string $text 二维码内容(URL)
 * @param   string $logo_path 二维码中间LOGO图片地址
 * @param   string $level 容错级别参数$level表示容错率，也就是有被覆盖的区域还能识别，
 *                                          分别是 L（QR_ECLEVEL_L，7%） M（QR_ECLEVEL_M，15%），
 *                                          Q（QR_ECLEVEL_Q，25%）H（QR_ECLEVEL_H，30%）；
 * @param   integer $size 参数$size表示生成图片大小，默认是6；参数$margin表示二维码周围边框空白区域间距值；
 * @param   integer $margin 参数$margin表示二维码周围边框空白区域间距值；
 * @param   boolen $saveandprint 参数$saveandprint表示是否保存二维码并显示
 * @return  array   ['path'=>$path,
 *                   'state'=>$state]   [二维码图片地址,返回状态]
 * @DEMO
 *    $qrcode = create_qrcode('http://www.improvcn.com','logo.png');
 *    $state = $qrcode['state'];
 *    if($state){
 *        $path = $qrcode['path'];
 *        echo '<img src="'. $path .'">';
 *    }
 */

function create_qrcode($params)
{
    $text = isset($params['text']) ? $params['text'] : false;
    $logo_path = isset($params['logo_path']) ? $params['logo_path'] : false;
    $level = isset($params['level']) ? $params['level'] : 'L';
    $size = isset($params['size']) ? $params['size'] : 6;
    $margin = isset($params['margin']) ? $params['margin'] : 2;
    $saveandprint = isset($params['saveandprint']) ? $params['saveandprint'] : false;

    $result['state'] = false;
    if ($text != FALSE) {
        $outfile = 'upload/qrcode/qrcode_' . randpw(3, 'A', true) . '.png';
        vendor('phpqrcode/phpqrcode');
        //    $text = 'http://www.improvcn.com';
        QRcode::png($text, $outfile, $level, $size, $margin);
        $logo = $logo_path;//准备好的logo图片
        $QR = $outfile;//已经生成的原始二维码图
        if ($logo !== FALSE) {
            $QR = imagecreatefromstring(file_get_contents($QR));
            $logo = imagecreatefromstring(file_get_contents($logo));
            $QR_width = imagesx($QR);//二维码图片宽度
            $QR_height = imagesy($QR);//二维码图片高度
            $logo_width = imagesx($logo);//logo图片宽度
            $logo_height = imagesy($logo);//logo图片高度
            $logo_qr_width = $QR_width / 5;
            $scale = $logo_width / $logo_qr_width;
            $logo_qr_height = $logo_height / $scale;
            $from_width = ($QR_width - $logo_qr_width) / 2;
            //重新组合图片并调整大小
            imagecopyresampled($QR, $logo, $from_width, $from_width, 0, 0, $logo_qr_width,
                $logo_qr_height, $logo_width, $logo_height);
            imagepng($QR, $outfile);

        }
        if (file_exists($outfile)) {
            $result['state'] = true;
            $result['path'] = $outfile;
        }
    }
    return $result;
}

function sigin_wx_card($time = 123131213, $card = 'p5A-ht3LWjdzJ2xmeyzu3lZh7Pkg', $nonce_str = false)
{

    $api_ticket = get_api_ticket('wx_card');
    $stringB = array(
//        'nonce_str'=>randpw(10),
        'api_ticket' => $api_ticket,
        'card_id' => $card,
        'timestamp' => strval($time)
    );
    $stringA = array_values($stringB);
    sort($stringA);
    $result = (sha1(implode('', $stringA)));
    return (array('sigin' => $result, 'stringA' => $stringB
//    ,'api_ticket'=>strval(intval(intval($time)/1000))
    ));
}


function getJSSDK()
{
    $msg = array('url' => get_url());
    $url = 'http://' .get_pc_host() . "/Simian/PlatformInterface/User/js_sdk";
    $signature = objectToArray(get($url, $msg));
    return $signature;

    //$this->assign('weixin2',$signPackage);

}

// $nonceStr   = randpw(30);
//$times_tamp = time();
//$request_url= get_url();
//$signature = sigin_jsapi($times_tamp, $nonceStr, $request_url);

function sigin_jsapi($times_tamp, $nonceStr, $request_url)
{
    return wei_xin_api()->getJsSign($request_url, $times_tamp, $nonceStr);
}

function sort_to_array($a)
{
    (ksort($a));
    $date = array();
    foreach ($a as $k => $v) {
        array_push($date, $k . '=' . $v);
    }
    $date_imp = implode('&', $date);
//    dump($date_imp);
    return $date_imp;
}

function get_uri()
{
    $php_self = $_SERVER['PHP_SELF'] ? $_SERVER['PHP_SELF'] : $_SERVER['SCRIPT_NAME'];
    $path_info = isset($_SERVER['PATH_INFO']) ? $_SERVER['PATH_INFO'] : '';
    dump($php_self);
//    $relate_url = isset($_SERVER['REQUEST_URI']) ? $_SERVER['REQUEST_URI'] : $php_self . (isset($_SERVER['QUERY_STRING']) ? '?' . $_SERVER['QUERY_STRING'] : $path_info);
    if (isset($_SERVER['REQUEST_URI'])) {
        $relate_url = $_SERVER['REQUEST_URI'];
    } else {
        if (isset($_SERVER['QUERY_STRING'])) {
            $relate_url = $php_self;
        } else {
            $relate_url = $php_self . ($path_info);
        }
    }
    return ($relate_url);
}

/**
 * 获取当前页面完整URL地址
 */
function get_url($ty = false, $t = false)
{
    $sys_protocal = isset($_SERVER['SERVER_PORT']) && $_SERVER['SERVER_PORT'] == '443' ? 'https://' : 'http://';
    $php_self = $_SERVER['PHP_SELF'] ? $_SERVER['PHP_SELF'] : $_SERVER['SCRIPT_NAME'];
    $path_info = isset($_SERVER['PATH_INFO']) ? $_SERVER['PATH_INFO'] : '';
    $relate_url = isset($_SERVER['REQUEST_URI']) ? $_SERVER['REQUEST_URI'] : $php_self . (isset($_SERVER['QUERY_STRING']) ? '?' . $_SERVER['QUERY_STRING'] : $path_info);
    if ($ty == FALSE) {
        return $sys_protocal . (isset($_SERVER['HTTP_HOST']) ? $_SERVER['HTTP_HOST'] : '') . $relate_url;
    } else {
        if ($t == FALSE) {
            return ($sys_protocal . (($_SERVER['HTTP_HOST'])));
        } else {
            return ((($sys_protocal . '.' . $_SERVER['HTTP_HOST'])));
        }

    }
}

function save_weixin_conf($token)
{

}

function get_weixin_conf()
{
    $m_sssmall_conf_weixin = M('sssmall_conf_weixin');

    return $m_sssmall_conf_weixin->where(1)->find();
}

/**
 * 功能 返回数据源类型查找条件
 * @param $group_date
 * @return array
 */
function get_array_name($group_date)
{
    $k = $group_date['k'];
    $name = $group_date['name'];
    if ($k == '0') {
        $na = array('name' => '全部' . $name . '会员');
    } elseif ($k == '1') {
        $na = array('name' => '30天内新注册' . $name . '会员');
    } elseif ($k == '2') {
        $na = array('name' => '7天内新注册' . $name . '会员');
    }
    return $na;
}

/**
 * 功能：添加默认分组数据来源，有添加没有更新
 * @param $k 三种类型 1 表示一个月，2表示7天 0 表示全部
 * @param $nu   包含的数量
 * @param $data
 */
function set_data_sources($group_date)
{
    $en_name = $group_date['en_name'];
    $k = $group_date['k'];
    $table_name = $group_date['table_name'];
    $data_sources = M('data_sources');
    if ($group_date['wx_info']) {
        $na = $group_date['wx_info'];
    } else {
        $na = get_array_name($group_date);
    }
    $data_source_id = $data_sources->where($na)->getField('id');

    if (!isset($data_source_id)) {
        $group_type_id = M('group_types')->where(array('en_name' => $en_name))->getField('id');
        $map = array(
            'activated_number' => 0,
            'total_number' => 0,
            'sort_number' => $k,
            'state' => 1,
            'created_at' => format_time(),
            'updated_at' => format_time(),
            'group_type_id' => $group_type_id,
        );
        $data_source_id = $data_sources->add(array_merge($map, $na));
    }
    M('group_members_records')->where(array('table_name' => $table_name,
        'data_source_id' => $data_source_id))->delete();
    $group_date['data_source_id'] = $data_source_id;
    return add_group_members_records($group_date);
}

/**
 * 功能：添加组成员与不同类型数据的关联记录
 * @param $group_date
 * @return int
 */
function add_group_members_records($group_date)
{
    $total = 0;
    $data_source_id = $group_date['data_source_id'];
    $table_name = $group_date['table_name'];
    $nu = get_number($group_date);
    $time = format_time();
    $data_reslt['state'] = 1;
    $data_reslt['data_source_id'] = $data_source_id;
    $data_reslt['table_name'] = $table_name;
    $data_reslt['created_at'] = $time;
    $data_reslt['updated_at'] = $time;
    $data = array();
    if (is_array($nu)) {
        $total = count($nu);
        foreach ($nu as $id) {
            $data_reslt['table_id'] = $id;
            array_push($data, $data_reslt);
        }
    } elseif (isset($nu)) {
        $total = $nu;
        array_push($data, $data_reslt);
    }
    M('data_sources')->where(array('id' => $data_source_id))->save(array('total_number' => $total, 'updated_at' => $time));
    M('group_members_records')->addAll($data);
    return $nu;
}

/**
 * 功能：返回查找数据源
 * @param $group_date
 * @return \Model|\Think\Model
 */
function get_ty($group_date)
{
    $ty_date = $group_date['ty_date'];
    $ty = $group_date['table_name'];
    if ($ty_date) {
        $ty = M($ty)->where($ty_date);
    } else {
        $ty = M($ty);
    }
    return $ty;
}

/**
 * 功能：得到分组人数或人ID的数组
 * @param $k //$k =1 表示一个月，＝2表示7天 ＝0 表示全部
 * @param $ty MODEL实例化
 * @return int  返回人数或数组
 */
function get_number($group_date)
{
    $k = $group_date['k'];
    $ty = get_ty($group_date);
    if ($k == 0) {
        $nu = $ty->count();
    } else {
        $nu = new_wx_user_info($group_date);
    }
    return $nu;
}

/**
 * 功能：指定天数的关注人数
 * @param $day  int     天数
 * @return      int     关注人数
 */
function new_wx_user_info($group_date)
{
    $date = $group_date['data'];
    $day = $group_date['k'];
    $ty = get_ty($group_date);
    $state = $date['st'] ? $date['st'] : false;
    $time_ty = $date['time_ty'] ? $date['time_ty'] : 'create_time';
    $end = time();
    if ($day == 1) {
        $begin = strtotime('-1 month');
    } elseif ($day == 2) {
        $begin = strtotime('-7 day');

    } else {
        $begin = strtotime('-1 month');

    }
    if ($state != FALSE) {
        $begin = date('Y-m-d H:i:s', $begin);
        $end = date('Y-m-d H:i:s', time());

    }

    $map[$time_ty] = array('between', array($begin, $end));
//        dump($map);
    if (in_array($day, array(1, 2))) {
        $wx_number = $ty->where($map)->getField('id', true);
    } elseif (in_array($day, array(3, 4, 5, 6, 7))) {
        $wx_number = get_wx_info_number($map, $day);
    }
    return $wx_number;
}

/**
 * 数组转对象
 * @param $arr
 * @return object
 */
function arrayToObject($arr)
{
    if (is_array($arr)) {
        return (object)array_map(__FUNCTION__, $arr);
    } else {
        return $arr;
    }
}

//        一个指定月内有记录的会员总人数
function get_month_common_user_array($map)
{
    $d = M('js_behavior_records')->where($map)->group('common_user_id,date(created_at)')->getField('common_user_id', true);
    $result = array();
    $results = array();
    foreach ($d as $id) {
        $result[$id] ? $result[$id] += 1 : $result[$id] = 1;
        $results[$result[$id]] ? array_push($results[$result[$id]], $id) : $results[$result[$id]] = [$id];
    }

    return (($results));
}

function get_map_jbr()
{
    $begin = strtotime('-3 month');
    $begin = date('Y-m-d H:i:s', $begin);
    $end = date('Y-m-d H:i:s', time());
    return array('created_at' => array('between', array($begin, $end)));
}

//    得到微信其它类型数据
function get_wx_info_number($map, $day)
{
    $nu = array();
    $results = get_month_common_user_array($map);
    $result = array();
    $d = array(1, 2, 5);
    foreach ($d as $i) {
        if (!isset($result[$i])) {
            $result[$i] = array();
        }
    }
    foreach (array_keys($results) as $id) {
        if ($id >= 5) {
            $result[5] = array_merge($result[5], $results[$id]);
        } elseif ($id < 5 && $id >= 2) {
            $result[2] = array_merge($result[2], $results[$id]);
        } elseif ($id == 1) {
            $result[1] = array_merge($result[1], $results[$id]);
        }
    }
    $js_behavior_record = M('js_behavior_records');

    if ($day == 3) {
        $nu = $result[5] ? $result[5] : array();
    } elseif ($day == 4) {
        $nu = $result[2] ? $result[2] : array();
    } elseif ($day == 5) {
        $nu = $result[1] ? $result[1] : array();
    } elseif ($day == 6) {
        $map = get_map_jbr();
        $common_user_ids = $js_behavior_record->where($map)->getField('common_user_id', true);
        if ($common_user_ids) {
            $map = array('common_user_id' => array('not in', $common_user_ids));
            $nu = $js_behavior_record->where($map)->getField('common_user_id', true);
        }
    } elseif ($day == 7) {
        $map = get_map_jbr();
        $common_user_ids = $js_behavior_record->where($map)->getField('common_user_id', true);
        if ($common_user_ids) {
            $openids = M('common_user')->where(array('id' => array('in', $common_user_ids)))->group('openid')->getField('openid', true);
            $map = array('openid' => array('not in', $openids));
            $nu = M('weixin_user_info')->where($map)->count();
        }
    }
    return $nu;
}

/**
 * 生成随机数方法
 * @param int $len 需要生成的随机数长度
 * @param string $format 随机数的组成(A_数字+大小字母,N_数字,C_大小字母),默认为A
 * @param null $time 是否添加时间戳 默认为false,
 * @return string               返回随机数
 */

function randpw($len = 8, $format = 'A', $time = false)
{
    $is_abc = $is_numer = 0;
    $password = $tmp = '';
    switch ($format) {
        case 'A':
            $chars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';
            break;
        case 'C':
            $chars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz';
            break;
        case 'N':
            $chars = '0123456789';
            break;
        default :
            $chars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';
            break;
    }
    mt_srand((double)microtime() * 1000000 * getmypid());
    while (strlen($password) < $len) {
        $tmp = substr($chars, (mt_rand() % strlen($chars)), 1);
        if (($is_numer <> 1 && is_numeric($tmp) && $tmp > 0) || $format == 'CHAR') {
            $is_numer = 1;
        }
        if (($is_abc <> 1 && preg_match('/[a-zA-Z]/', $tmp)) || $format == 'NUMBER') {
            $is_abc = 1;
        }
        $password .= $tmp;
    }
    if ($format == 'A') {
        if ($is_numer <> 1 || $is_abc <> 1 || empty($password)) {
            $password = randpw($len, $format);
        }
    }
    if ($time != FALSE) {
        $password = time() . '_' . $password;
    }
    return $password;
}


/**
 * 数据分页:分页类和page方法的实现分页
 * @author zk
 * @param Model $mod 请求的实例化对象
 * @param integer $p 当前的页数使用 $_GET[p]获取
 * @param array $map 查询条件,默认为空数组
 * @param string $order 排序条件,默认为更新时间倒序
 * @param integer $page_count 每页显示数量,默认为10
 * @assign list     $list       赋值数据集
 * @assign page     $page       赋值分页输出
 */

function get_page_message($mod, $p, $map = [], $order = 'updated_at desc', $page_count = 10)
{
    $User = M($mod);
    $users = $User->where($map);
    $list = $users->order($order)->page($p . ',$page_count')->select();
    $count = $users->count();// 查询满足要求的总记录数
    $Page = new \Think\Page($count, $page_count);// 实例化分页类 传入总记录数和每页显示的记录数
    $show = $Page->show();// 分页显示输出
    foreach ($map as $key => $val) {
        $Page->parameter[$key] = urlencode($val);
    }
    $this->assign('list', $list);// 赋值数据集
    $this->assign('page', $show);// 赋值分页输出
//    return array('list'=>$list,'page'=>$show);
}

/**
 * 获取微信平台TOKENT
 * @author zk
 * @return string
 */
function get_tokens()
{

    $token = wei_xin_api()->access_token;
    return $token;
}

function getToken()
{
    return get_tokens();
}

/**
 * 获取B总URL
 * @author zk
 * @param string $ty (pc_host/mobile_host)
 * @return string | null
 */
function get_pc_host($ty = 'pc_host')
{
    return M('sssmall_conf_weixin')->where(1)->getField($ty);
}

function get_mobile_host($ty = 'mobile_host')
{
    return M('sssmall_conf_weixin')->where(1)->getField($ty);
}

function isMobile()
{
    // 如果有HTTP_X_WAP_PROFILE则一定是移动设备
    if (isset ($_SERVER['HTTP_X_WAP_PROFILE']))
        return true;

    //此条摘自TPM智能切换模板引擎，适合TPM开发
    if (isset ($_SERVER['HTTP_CLIENT']) && 'PhoneClient' == $_SERVER['HTTP_CLIENT'])
        return true;
    //如果via信息含有wap则一定是移动设备,部分服务商会屏蔽该信息
    if (isset ($_SERVER['HTTP_VIA']))
        //找不到为flase,否则为true
        return stristr($_SERVER['HTTP_VIA'], 'wap') ? true : false;
    //判断手机发送的客户端标志,兼容性有待提高
    if (isset ($_SERVER['HTTP_USER_AGENT'])) {
        $clientkeywords = array(
            'nokia', 'sony', 'ericsson', 'mot', 'samsung', 'htc', 'sgh', 'lg', 'sharp', 'sie-', 'philips', 'panasonic', 'alcatel', 'lenovo', 'iphone', 'ipod', 'blackberry', 'meizu', 'android', 'netfront', 'symbian', 'ucweb', 'windowsce', 'palm', 'operamini', 'operamobi', 'openwave', 'nexusone', 'cldc', 'midp', 'wap', 'mobile'
        );
        //从HTTP_USER_AGENT中查找手机浏览器的关键字
        if (preg_match("/(" . implode('|', $clientkeywords) . ")/i", strtolower($_SERVER['HTTP_USER_AGENT']))) {
            return true;
        }
    }
    //协议法，因为有可能不准确，放到最后判断
    if (isset ($_SERVER['HTTP_ACCEPT'])) {
        // 如果只支持wml并且不支持html那一定是移动设备
        // 如果支持wml和html但是wml在html之前则是移动设备
        if ((strpos($_SERVER['HTTP_ACCEPT'], 'vnd.wap.wml') !== false) && (strpos($_SERVER['HTTP_ACCEPT'], 'text/html') === false || (strpos($_SERVER['HTTP_ACCEPT'], 'vnd.wap.wml') < strpos($_SERVER['HTTP_ACCEPT'], 'text/html')))) {
            return true;
        }
    }
    return false;
}

/**
 * 获取api_ticket
 * @author zk
 * @param string $ty ticket类型(wx_card/jsapi)
 * @return string
 */
function get_ticket($ty)
{
    $get_ticket['ticket'] = wei_xin_api()->get_js_token($ty);
    return $get_ticket;
}



function get_inve_openid($openids)
{
    $inv_openid = M('weixin_user_info')->where(array('openid' => array('in', $openids)))->getField('openid', true);
    if ($inv_openid) {
        $inv_openid = array_unique($inv_openid);
        $openids = array_diff($openids, $inv_openid);
    } else {

    }
    return $openids;

}


function get_inve_wx_user_info_id($ids)
{
    $wx_user_info_ids = M('multi_user_type_associates')->where('wx_user_info_id is not null')->getField('wx_user_info_id', true);
    if ($wx_user_info_ids) {
        $wx_user_info_ids = array_unique($wx_user_info_ids);
        $ids = array_diff($ids, $wx_user_info_ids);
    }
    return $ids;

}


/**
 * 功能 OBJECT TO ARRAY
 * @param $array
 * @return array
 */
function objectToArray($array)
{
    return object_array($array);
}

function object_array($array)
{
    if (is_object($array)) {
        $array = (array)$array;
    }
    if (is_array($array)) {
        foreach ($array as $key => $value) {
            $array[$key] = object_array($value);
        }
    }
    return $array;
}

function update_wx_user($openids, $weixin, $tab = 'weixin_user_info')
{
    $map_openids['openid'] = array('in', $openids);
    $tab = M($tab);
    $tab->where($map_openids)->setField(array('is_subscribe' => 1));
    $old_openids = $tab->where($map_openids)->getField('openid', true);
    $new_openids = array_diff($openids, $old_openids);
    $f = format_time();
    $wei_xin_api = $weixin;
    $weixin_user_info_data = array();
    foreach ($new_openids as $openid) {
        $user = $wei_xin_api->user($openid);
        $user['updated_at'] = $f;
        $user['is_subscribe'] = 1;
        $user['created_at'] = $f;
        $weixin_user_info_data[] = ($user);
    }

//    echo(json_encode($weixin_user_info_data));
    $tab->addAll($weixin_user_info_data);
    add_muntails($openids);


//    return array('wx_user_infos'=>$weixin_user_info_data,'new_openids'=>$new_openids,'old_openids'=>$old_openids);

}

//得到微信用户数据简单
function wx_user_info_add($openids)
{
    if ($openids) {
        $openids = get_inve_openid($openids);
        $date = array();
        $i = 0;
        $t = format_time();
        foreach ($openids as $openid) {
            $date[$i] = array('openid' => $openid, 'is_subscribe' => 1, 'updated_at' => $t, 'created_at' => $t);
            $i += 1;
        }
        if (count($date) > 0) {
            (M('weixin_user_info')->addAll($date));
        }

    }
}

//得到中间表微信简单数据
function get_multi_user_type_associate()
{
    $multi_user_type_associates = M('multi_user_type_associates');
    $ids = M('weixin_user_info')->where(1)->getField('id', true);
    if (count($ids) > 0) {
        $ids = get_inve_wx_user_info_id($ids);
        $nu = (ceil(count($ids) / 1000.0));
        $f = format_time();
        $date = array_group($ids, $nu, 1000);
        dump($ids);
        dump($date);
        foreach ($date as $k => $id) {
            $da = array();
            $i = 0;
            foreach ($id as $key => $wx_is) {
                $da[$i] = array('wx_user_info_id' => $wx_is, 'state' => 1,
                    'is_blacklist' => 0, 'is_subscribe' => 1, 'is_verification' => 0, 'created_at' => $f, 'updated_at' => $f);
                $i += 1;
            }
            if (count($da) > 0) {
                dump($multi_user_type_associates->addAll($da));
            }

        }
    }
}

/*
$arrF：数组
$user_count：分成几组
$group_num：每组多少个
*/
function array_group($arrF, $user_count, $group_num)
{
    for ($i = 0; $i < $user_count; $i++) {
        if ($i == $user_count - 1) {
            $arrT[] = array_slice($arrF, $i * $group_num);
        } else {
            $arrT[] = array_slice($arrF, $i * $group_num, $group_num);
        }
    }
    return $arrT;
}

/**
 * 接受josn返回函数
 * @author zk
 * @param string $url 请求的网址
 * @param integer|string|array $data 可发送过去的数据
 * @return integer|string|array 任何可能的返回数据
 */
function get($url, $param = array())
{
    if (!is_array($param)) {
        throw new Exception("参数必须为array");
    }
    $p = '';
    foreach ($param as $key => $value) {
        $p = $p . $key . '=' . $value . '&';
    }
    if (preg_match('/\?[\d\D]+/', $url)) {//matched ?c
        $p = '&' . $p;
    } else if (preg_match('/\?$/', $url)) {//matched ?$
        $p = $p;
    } else {
        $p = '?' . $p;
    }
    $p = preg_replace('/&$/', '', $p);
    $url = $url . $p;
    //echo $url;
    $httph = curl_init($url);
    $timeout = 5;

    curl_setopt($httph, CURLOPT_SSL_VERIFYPEER, 0);
    curl_setopt($httph, CURLOPT_SSL_VERIFYHOST, 1);
    curl_setopt($httph, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($httph, CURLOPT_USERAGENT, "Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.0)");
    curl_setopt($httph, CURLOPT_CONNECTTIMEOUT, $timeout);

    curl_setopt($httph, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($httph, CURLOPT_HEADER, 0);
    $rst = curl_exec($httph);
    curl_close($httph);
    $rsts = json_decode($rst);
    if ($rsts == null) {
        $rsts = $rst;
    }
    return $rsts;
}

function save_wx_user($openid, $tab = 'weixin_user_info')
{
    $map_wx = array('openid' => $openid);
    $weixin_user_info = M($tab);
    $wx_user_info_id = $weixin_user_info->where($map_wx)->getField('id');
    $f = format_time();
    $wei_xin_subscribe_log_data['openid'] = $openid;
    $wei_xin_subscribe_log_data['created_at'] = $f;
    $wei_xin_subscribe_log_data['is_subscribe'] = 1;
    $weixin_user_info_data['updated_at'] = $f;
    $weixin_user_info_data['is_subscribe'] = 1;
    M('wei_xin_subscribe_log')->add($wei_xin_subscribe_log_data);
    if ($wx_user_info_id) {
        $weixin_user_info->where($map_wx)->save($weixin_user_info_data);
    } else {
        $user = wei_xin_api()->user($openid);
        if ($user) {
            $data = object_array($user);
            $weixin_user_info_data['created_at'] = $f;
            $weixin_user_info_data = MergeArray($data, $weixin_user_info_data);
            $wx_user_info_id = $weixin_user_info->add($weixin_user_info_data);
        }
    }
    if ($wx_user_info_id) {
        add_muntail($openid);
    }
}

function get_wx_data_source_ids()
{
    $group_type_id = M('group_types')->where(array('en_name' => "wx_group"))->getField('id');
    $condition['name'] = array(array('eq', '30天内新注册微信会员'), array('eq', '7天内新注册微信会员'), 'or');
    $condition['group_type_id'] = $group_type_id;
    $data_source_ids = M('data_sources')->where($condition)->getField('id', true);
    return $data_source_ids ? $data_source_ids : [];
}

function get_data_source_ids($name)
{
    if ($name) {
        $condition['name'] = array('in', $name);
        $data_source_ids = M('data_sources')->where($condition)->getField('id', true);
        return $data_source_ids ? $data_source_ids : [];
    } else {
        return [];
    }

}

function updated_group_tags($is_subscribe, $multi_user_type_associates_id)
{
    \Think\Log::write($is_subscribe . 'updated_group_tags =====' . $multi_user_type_associates_id, 'WARN');

    $f = format_time();
    $data_source_ids = get_wx_data_source_ids();
    if ($data_source_ids && $multi_user_type_associates_id) {
        $map = array('data_source_id' => array('in', $data_source_ids), 'multi_user_type_associate_id' => $multi_user_type_associates_id);
        $multi_user_type_associate_group_tag_db = M('multi_user_type_associate_group_tags');
        if ($is_subscribe) {
            \Think\Log::write("查看添加内容=map=" . json_encode($map), 'WARN');
            $mutagt = $multi_user_type_associate_group_tag_db->where($map)->find();
            if ($mutagt) {
                \Think\Log::write("查看添加内容=mutagt=" . json_encode($mutagt), 'WARN');

            } else {
                foreach ($data_source_ids as $data_source_id) {
                    $multi_user_type_associate_group_tags[] = array(
                        'data_source_id' => $data_source_id,
                        'multi_user_type_associate_id' => $multi_user_type_associates_id,
                        'updated_at' => $f, 'created_at' => $f, 'state' => 1);
                }
                \Think\Log::write("查看添加内容=multi_user_type_associate_group_tags=" . json_encode($multi_user_type_associate_group_tags), 'WARN');

                $multi_user_type_associate_group_tag_db->addAll($multi_user_type_associate_group_tags);
            }
        } else {
            $multi_user_type_associate_group_tag_db->where($map)->delete();
        }
    }

}

function get_wx_user_info($openid)
{
    $wx = wei_xin_api();

    $map_wx = array('openid' => $openid);
    $weixin_user_info_db = M('weixin_user_info');
    $wx_user_info = $weixin_user_info_db->where($map_wx)->find();
    $get_user_info = $wx->getUserInfo($openid);
    if ($get_user_info['openid']) {
        /*是否关注*/
        $is_subscribe = $get_user_info['subscribe'];
        $get_user_info['is_subscribe'] = $is_subscribe;
        unset($get_user_info['subscribe']);
        if ($is_subscribe == 1) {
            unset($get_user_info['tagid_list']);
        }
        $f = format_time();
        $get_user_info['updated_at'] = $f;

        if ($wx_user_info) {
            /*update*/
            if ($wx_user_info['subscribe_time']) unset($get_user_info['subscribe_time']);
            if ($get_user_info['nickname']) $get_user_info['nickname'] = EmojiUtf8($get_user_info['nickname']);
            $weixin_user_info_db->where($map_wx)->save($get_user_info);
            $wx_user_id = $wx_user_info['id'];
        } else {
            /*create*/
            $get_user_info['created_at'] = $f;
            if ($get_user_info['nickname']) {
                $get_user_info['nickname'] = EmojiUtf8($get_user_info['nickname']);
            }
            $wx_user_id = $weixin_user_info_db->add($get_user_info);
        }
        return array(true, $wx_user_id, $is_subscribe);

    } else {
        return array(false, $wx->errMsg, $wx->errCode);

    }

}

function EmojiUtf8($nickname)
{

    $nickname = utf8_encode($nickname);
//    $mm=utf8_decode($utfresult);

    return $nickname;
}

function Utf8Emoji($nickname)
{

    $nickname = utf8_decode($nickname);
//    $mm=utf8_decode($utfresult);

    return $nickname;
}

function emoji_to_unified($nickname)
{

//    $data = emoji_docomo_to_unified($data);   # DoCoMo devices
//    $data = emoji_kddi_to_unified($data);     # KDDI & Au devices
//    $data = emoji_softbank_to_unified($data); # Softbank & pre-iOS6 Apple devices
//    $data = emoji_google_to_unified($data);   # Google Android devices

    $nickname = emoji_google_to_unified($nickname);
    return $nickname;
}

function unified_to_emoji($nickname)
{
//    $data = emoji_unified_to_docomo($data);   # DoCoMo devices
//    $data = emoji_unified_to_kddi($data);     # KDDI & Au devices
//    $data = emoji_unified_to_softbank($data); # Softbank & pre-iOS6 Apple devices
    $nickname = emoji_unified_to_google($nickname);   # Google Android devices

//    $nickname = emoji_google_to_unified($nickname);
    return $nickname;
}

/*$ty 如果添加说明是在数据库中取出的*/
function EmojiToHtml($nickname, $ty = false)
{
//    dump($nickname);

    if ($ty) $nickname = Utf8Emoji($nickname);
    $nickname = emoji_unified_to_html($nickname);/*页面展示的值*/
    return $nickname;
}

function get_mu_msg($openid)
{
    $result = array();
    if ($openid) {
        list($ty, $wx_user_id, $is_subscribe, $common_user_id) = get_common_user($openid);
        if ($ty) {
            $common_user_db = M('common_user');
            $weixin_user_info_db = M('weixin_user_info');

            /*查看中间表是否存在数据*/
            $multi_user_type_associates_db = D('multi_user_type_associates');
            $mu_wx = $multi_user_type_associates_db->where(array('wx_user_info_id' => $wx_user_id))->find();
            $mu_ele = $multi_user_type_associates_db->where(array('common_user_id' => $common_user_id))->find();

            if (!$mu_wx && !$mu_ele) {
                /*拉取wx用户信息，写入wx用户表*/
                if ($wx_user_id) $data['wx_user_info_id'] = $wx_user_id;
                /*写入电子会员表*/
                if ($common_user_id) $data['common_user_id'] = $common_user_id;
                if ($data) {
                    $datas['state'] = 1;
                    $datas['created_at'] = format_time();
                    $datas['updated_at'] = format_time();
                    $data = MergeArray($datas, $data);
                    $multi_user_type_associates_db->add($data);
                }
                /*写入中间表*/
            } else {
                if ($mu_wx) {
                    /*创建电子会员，补全中间表*/
                    $wx_user_info = $weixin_user_info_db->where(array('openid' => $openid))->find();
                    unset($wx_user_info['id']);
                    if (!$common_user_id) {
                        $wx_user_info['img'] = $wx_user_info['headimgurl'];
                        $udata = common_user_array($wx_user_info);
                        $common_user_id = $common_user_db->add($udata);
                    }

                    $crm_d = array(
                        'common_user_id' => $common_user_id,
                        'updated_at' => format_time(),
                    );
                    $multi_user_type_associates_db->where(array('wx_user_info_id' => $wx_user_id))->setField($crm_d);
                }
                if ($mu_ele) {
                    /*创建wx会员，补全中间表*/
                    $get_user_info = wei_xin_api()->getUserInfo($openid);
                    if ($get_user_info['openid']) {
                        $wx_d = $get_user_info;
                        $wx_d['nickname'] = EmojiUtf8($wx_d['nickname']);
                        $wx_d['is_subscribe'] = $is_subscribe;
                        $wx_d['updated_at'] = format_time();
                        if ($wx_user_id) {
                            $weixin_user_info_db->where(array('openid' => $openid))->save($wx_d);

                        } else {
                            $wx_d['created_at'] = format_time();
                            $wx_user_id = $weixin_user_info_db->add($wx_d);
                        }
                        $wx_d = array(
                            'wx_user_info_id' => $wx_user_id,
                            'common_user_id' => $common_user_id,
                            'updated_at' => format_time(),
                        );
                        $multi_user_type_associates_db->where(array('common_user_id' => $common_user_id))->setField($wx_d);
                    }
                }

            }

        } else {
            $result = array('msg' => $wx_user_id);
        }
    } else {
        $result = array('msg' => false);
    }
    return ($result);
}

function delete_mu_other_date($is_subscribe, $common_user_db, $weixin_user_info_db, $multi_user_type_associates_db, $common_user_id, $crm_id, $wx_user_id, $mu_wx, $mu_crm, $mu_ele)
{
    $wx_d = array(
        'wx_user_info_id' => '',
        'updated_at' => format_time(),
        'is_subscribe' => '',
    );
    $multi_user_type_associates_db->where(array('wx_user_info_id' => $wx_user_id))->setField($wx_d);
    $wx_d = array(
        'wx_user_info_id' => $wx_user_id,
        'updated_at' => format_time(),
        'is_subscribe' => $is_subscribe,
    );
    $multi_user_type_associates_db->where(array('common_user_id' => $common_user_id))->setField($wx_d);

    $mu_ele = $multi_user_type_associates_db->where(array('common_user_id' => $common_user_id))->find();
    $mu_wx = $multi_user_type_associates_db->where(array('wx_user_info_id' => $wx_user_id))->find();

    update_mu_date($is_subscribe, $common_user_db, $weixin_user_info_db, $multi_user_type_associates_db, $common_user_id, $crm_id, $wx_user_id, $mu_wx, $mu_crm, $mu_ele);

}

function update_mu_date($is_subscribe, $common_user_db, $weixin_user_info_db, $multi_user_type_associates_db, $common_user_id, $crm_id, $wx_user_id, $mu_wx, $mu_crm, $mu_ele)
{
    if ($mu_crm) {

        if ($mu_ele == $mu_crm) {
            $crm_d = array('is_subscribe' => $is_subscribe,
                'updated_at' => format_time(),
            );
            $multi_user_type_associates_db->where(array('crm_user_id' => $crm_id))->setField($crm_d);
        } else {
            change_mu_date($is_subscribe, $common_user_db, $weixin_user_info_db,
                $multi_user_type_associates_db, $common_user_id, $crm_id, $wx_user_id, $mu_wx, $mu_crm, $mu_ele);
        }
    } else {
        $crm_d = array(
            'is_subscribe' => $is_subscribe,
            'crm_user_id' => null,
            'membership_level_id' => null,
            'updated_at' => format_time(),
            'is_verification' => 0,
        );
        if ($common_user_id) {
            $common_user = $common_user_db->where(array('id' => $common_user_id))->find();
            if ($common_user) {
                $crm_d['sex'] = $common_user['sex'];
                if (isTel($common_user['tel'])) {
                    $crm_d['phone'] = $common_user['tel'];
                    $crm_d['is_dianzi'] = 1;
                } else {
                    $crm_d['phone'] = null;
                    $crm_d['is_dianzi'] = 0;
                }
            }
        }
        $multi_user_type_associates_db->where(array('common_user_id' => $common_user_id))->save($crm_d);
    }
}

function change_mu_date($is_subscribe, $common_user_db, $weixin_user_info_db, $multi_user_type_associates_db, $common_user_id, $crm_id, $wx_user_id, $mu_wx, $mu_crm, $mu_ele)
{
    $crm_d = array(
        'crm_user_id' => '',
        'membership_level_id' => '',
        'updated_at' => format_time(),
        'is_verification' => 0,
    );
    $comd = $common_user_db->where(array('id' => $mu_crm['common_user_id']))->find();
    if ($comd) {
        if (isTel($comd['tel'])) $crm_d['phone'] = $comd['tel'];
        $crm_d['sex'] = $comd['sex'];
    }
    $comd = $weixin_user_info_db->where(array('id' => $mu_crm['wx_user_info_id']))->find();
    if ($comd) {
        $crm_d['is_subscribe'] = $comd['is_subscribe'];
    }
    if ($crm_id) $multi_user_type_associates_db->where(array('crm_user_id' => $crm_id))
        ->setField($crm_d);

    $crm_d = array(
        'is_subscribe' => $is_subscribe,
        'updated_at' => format_time(),
        'is_verification' => 0,
    );
    if ($crm_id) $crm_d['crm_user_id'] = $crm_id;
    if ($mu_ele['membership_level_id']) $crm_d['membership_level_id'] = $mu_ele['membership_level_id'];
    if ($mu_ele) $crm_d['sex'] = $mu_ele['sex'];
    dump($mu_ele);
    $multi_user_type_associates_db->where(array('common_user_id' => $common_user_id))->save($crm_d);

}

function get_common_user($openid)
{
    $map_wx = array('openid' => $openid);
    $common_user_db = M('common_user');
    $weixin_user_info_db = M('weixin_user_info');
    $common_user = $common_user_db->where($map_wx)->find();
    list($ty, $wx_user_id, $is_subscribe) = get_wx_user_info($openid);
//    dump($ty,$wx_user_id,$is_subscribe);die;
    if ($ty) {
        if ($common_user) {
            $wx_user_info = $weixin_user_info_db->where($map_wx)->find();

            $common_user_db->where($map_wx)
                ->save(array('is_subscribe' => $is_subscribe, 'nickname' => $wx_user_info['nickname']));
            $common_user_id = $common_user['id'];
        } else {
            $wx_user_info = $weixin_user_info_db->where($map_wx)->find();
            unset($wx_user_info['id']);
            $wx_user_info['img'] = $wx_user_info['headimgurl'];
            $udata = common_user_array($wx_user_info);
//            $ty = 1;
//            for ($i = 0; $i < 10; $i++) {
//                try {
            $common_user_id = $common_user_db->add($udata);
//                    M('common_user')->add(array('nickname'=>11));
//                } catch (\Exception $e) {
//                    $ty = 0;
//                }
//                if ($ty) {
//                    break;
//                }
//            }
        }
        return array(true, $wx_user_id, $is_subscribe, $common_user_id);
    } else {
        return array(false, $wx_user_id, $is_subscribe, '');
    }


}

function common_user_array($msg)
{
    $wx_msg = array(
//        'word' => '',
        'nickname' => '',
        'username' => randpw(20, 'A') . time(),
        'email' => randpw(20, 'A') . time(),
        'tel' => '',
        'xyz' => '',
        'img' => '',
        'password' => '',
        'create_time' => time(),
        'create_ip' => get_client_ip(1),
        'last_time' => time(),
        'last_ip' => 0,
        'login_num' => 0,
        'is_ok' => 0,
        'openid' => '',
        'sex' => 0,
        'qrcode' => '',
        'mibao' => '',
        'mibaokey' => '',
        'name' => '',
        'xueli' => '',
        'zinv' => '',
        'idkey' => '',
        'is_dianzi' => 0,
        'is_shiti' => 0,
        'xingbie' => '',
        'is_sms' => '',
        'is_shouhuo' => 0,
        'is_jifen' => 0,
        'status' => 1,
    );
    $wx_msg = MergeArray($wx_msg, $msg);
    return $wx_msg;
}

function add_muntail($openid, $is_subscribe = 1)
{
    \Think\Log::write('测试日志信息' . 'add_muntail--4530__' . M('common_user')->count(), 'WARN');

    get_mu_msg($openid);
    $common_user_id = M('common_user')->where(array('openid' => $openid))->getField('id');
    $muta_id = M('multi_user_type_associates')->where(array('common_user_id' => $common_user_id))->getField('id');
    return array($muta_id, $common_user_id);
}

//function add_muntail($openid, $is_subscribe = 1)
//{
//    $f = format_time();
//    $map_wx = array('openid' => $openid);
//    $wx_user_info = M('weixin_user_info')->where($map_wx)->find();
//
//    $common_user_db = M('common_user');
//    $common_user = $common_user_db->where($map_wx)->find();
//    $multi_user_type_associates_map1['wx_user_info_id'] = $wx_user_info['id'];
//    $data = array('is_dianzi' => 0, 'wx_user_info_id' => $wx_user_info['id'], 'is_subscribe' => $is_subscribe, 'updated_at' => $f);
//    if ($common_user) {
//        $data['is_dianzi'] = $common_user['is_dianzi'];
//        $common_user_id = $common_user['id'];
//    } else {
//        unset($wx_user_info['id']);
//        $udata = $wx_user_info;
//        $udata['username'] = md5(uniqid());
//        $udata['tel'] = md5(uniqid());
//        $udata['email'] = md5(uniqid());
//        $udata['img'] = $wx_user_info['headimgurl'];
//        $udata['create_time'] = time();
//        $udata['create_ip'] = get_client_ip(1);
//        $udata['status'] = 1;
//        $common_user_id = M('common_user')->add($udata);
//    }
//    $multi_user_type_associates_map1['common_user_id'] = $common_user_id;
//    $multi_user_type_associates_map1['_logic'] = 'OR';
//    $data['common_user_id'] = $common_user_id;
//
//    $multi_user_type_associates = D('YiPHP/multi_user_type_associates');
//    $multi_user_type_associates_id = $multi_user_type_associates->where($multi_user_type_associates_map1)->getField('id');
//    if ($multi_user_type_associates_id) {
//        $data['updated_at'] = $f;
//        $multi_user_type_associates->where($multi_user_type_associates_map1)->save($data);
//        updated_group_tags($is_subscribe, $multi_user_type_associates_id);
//        return $multi_user_type_associates_id;
//
//    } else {
//        $data['state'] = 1;
//        $data['phone'] = $common_user['tel'];
//        $data['sex'] = $common_user['sex'] ? $common_user['sex'] : $wx_user_info['sex'];
//        $data['is_blacklist'] = 0;
//        $data['is_verification'] = 0;
//        $data['created_at'] = $f;
//        if ($is_subscribe) {
//            $data_source_ids = get_wx_data_source_ids();;
//            foreach ($data_source_ids as $data_source_id) {
//                $data['multi_user_type_associate_group_tags'][] = array('data_source_id' => $data_source_id, 'created_at' => $f, 'updated_at' => $f, 'state' => 1);
//            }
//        }
//
//        return $multi_user_type_associates->relation(true)->add($data);
//    }
//}

function my_encoding($data, $to)
{
    $encode_arr = array('UTF-8', 'ASCII', 'GBK', 'GB2312', 'BIG5', 'JIS', 'eucjp-win', 'sjis-win', 'EUC-JP');
    $encoded = mb_detect_encoding($data, $encode_arr);
    $data = mb_convert_encoding($data, $to, $encoded);
    return $data;
}

/**
 *    功能: 用户卡券过滤功能(卡券浏览时卡券服务提供用户手机号与卡券组有效卡券ID集合,B总返回可查看的卡券集合)
 *    params json {'tel_num':1,'card_id':['12','22',.....]}
 *    return json {"card_ids":['12','22',....]}
 */

function handleTelNum($datas)
{
    $card_id = $datas["card_id"];
    $tel = $datas["tel_num"];
    $openid = $datas["openid"];
    $table_id = $datas["table_id"];
    $results['openid'] = $openid;
    $results['tel_num'] = $tel;
    $results['card_id'] = $card_id;
    $results['table_id'] = $table_id;
    $results['status'] = true;

    $data["openid"] = $openid;
    $data["tel_num"] = $tel;
    $map_gmr['card_id'] = array('in', array_values(array_unique($card_id)));
    $map_gmr['table_id'] = $table_id;
    $card_out_crm_group_db = M('card_crmgroup_related');
    //得到卡券对应的所有用户组标签
    $group_ids = array_values(array_unique($card_out_crm_group_db->where($map_gmr)->getField('data_source_id', true)));

    $results['group_ids'] = $group_ids;
//    得到用户手机号与openid所在的组
//    1.1手机号是否在外部组中
    $data_source_ids = M('intermediate_records')->where("phone_number = $tel")->getField('data_source_id', true);
//     得到卡券发放外部组集合
    $group_ids_new = $data_source_ids ? array_intersect(array_values(array_unique(($data_source_ids))), $group_ids) : [];
    $card_ids = [];
    $results['state'] = false;
    if ($group_ids_new) {
//        说明当前卡券组中的卡有发送给外部组的
        $card_ids = $card_out_crm_group_db->where(MergeArray($map_gmr, array('data_source_id' => array('in', $group_ids_new))))->getField('card_id', true);
        $results['state'] = true;
    }

//    2.1openid是否在电子会员中并返回组集合586
//    $common_user_condition['tel'] = $tel;
    $common_user_condition['openid'] = $openid;
//    $common_user_condition['_logic'] = 'OR';
    $common_user_id = M('common_user')->where(MergeArray(array('is_dianzi' => 1), $common_user_condition))->getField('id');
//    2.2手机号是否在CRM会员中并返回组集合
    $crm_user_id = M('customer_relationship_managements')->where("mobile = $tel")->getField('id');
//    2.3openid是否在CRM会员中并返回组集合
    $weixin_user_info_id = M('weixin_user_info')->where(MergeArray(array('is_subscribe' => 1), $common_user_condition))->getField('id');
    $muta = [];
    $group_name = array();
    if ($weixin_user_info_id || $common_user_id || $crm_user_id) {
        if ($weixin_user_info_id) {
            $condition['wx_user_info_id'] = $weixin_user_info_id;
            array_push($group_name, '全部微信会员');
        }
        if ($common_user_id) {
            $condition['common_user_id'] = $common_user_id;
            array_push($group_name, '全部电子会员');
        }
        if ($crm_user_id) {
            $condition['crm_user_id'] = $crm_user_id;
            array_push($group_name, '全部CRM会员');
        }

        $null_data_sources = get_data_source_ids($group_name);
        $null_data_source = array_intersect($null_data_sources, $group_ids);
        if ($null_data_sources && $null_data_source) {

            $card_id_s = $card_out_crm_group_db->where(MergeArray($map_gmr, array('data_source_id' => array('in', $null_data_source))))->getField('card_id', true);
            if ($card_id_s) {
                foreach ($card_id_s as $card_id) {
                    array_push($card_ids, $card_id);
                }
            }
        }

        $condition['_logic'] = 'OR';
        $where['_complex'] = $condition;
        $where['is_blacklist'] = 0;
        $muta = D('YiPHP/multi_user_type_associates')->relation('multi_user_type_associate_group_tags')->where($where)->find();
    }

    if ($muta) {
        $group_members_records = $muta['multi_user_type_associate_group_tags'];
        $results['group_members_records'] = json_encode($group_members_records);
        if ($group_members_records) {
            foreach ($group_members_records as $group_members_record) {
                if (in_array($group_members_record['data_source_id'], $group_ids)) {
                    $map_gmr['data_source_id'] = $group_members_record['data_source_id'];
                    $card_id = $card_out_crm_group_db->where($map_gmr)->getField('card_id');
                    $results['map_gmr'][] = json_encode($map_gmr);
                    if ($card_id) {
                        array_push($card_ids, $card_id);
                        $results['card_id_dd'][] = $card_id;
                    }

                }
                $results['data_source_ids'][] = $group_members_record['data_source_id'];
            }
            $results['state'] = true;
        }
    } else {
        $results['status'] = false;

    }
    $weixin_user_info_id = M('weixin_user_info')->where(MergeArray(array('is_subscribe' => 0), $common_user_condition))->getField('id');
    if ($weixin_user_info_id) {
        $is_blacklist = D('YiPHP/multi_user_type_associates')->where("wx_user_info_id=$weixin_user_info_id")->getField('is_blacklist');
        if (!$is_blacklist) {
            $results['status'] = true;
        } else {
            $results['status'] = false;
        }
    }

    if ($card_ids && $results['status']) {
        $card_ids = array_values(array_unique($card_ids));
    } else {
        $card_ids = [];
    }
    $results['card_ids'] = $card_ids;
    return $results;

}

function ArrayUnique($array)
{
    return array_values(array_filter(array_unique($array)));

}

function get_db_prefix()
{
    return C("DB_PREFIX");

}

function handleTelNums($datas)
{
    $card_id = $datas["card_id"];
    $tel = $datas["tel_num"];
    $openid = $datas["openid"];

    $data["openid"] = $openid;
    $data["tel_num"] = $tel;
//		$tel = json_decode(htmlspecialchars_decode($tel));
//    $card_id = json_decode(htmlspecialchars_decode($card_id));
    $results['state'] = false;
    $results['status'] = true;
    $results['tel_num'] = $tel;
    $results['cardid'] = $card_id;
    $map_0['phone_number'] = $tel;
//		$intermediate_records = M('intermediate_records')->where($map_0)->select();
    $results['card_ids'] = array();
    if (isset($card_id)) {
        $weixin_user_info_id = M('weixin_user_info')->where(array('openid' => $openid))->getField('id');
        $common_user_id = M('common_user')->where(array('openid' => $openid))->getField('id');
        $crm_user_id = M('customer_relationship_managements')->where(array('mobile' => $tel))->getField('id');

        $condition['wx_user_info_id'] = $weixin_user_info_id;
        $condition['common_user_id'] = $common_user_id;
        $condition['crm_user_id'] = $crm_user_id;
        $condition['_logic'] = 'OR';
        $where['_complex'] = $condition;
        $where['is_blacklist'] = 1;
        $is_blacklist = M('multi_user_type_associates')->where($where)->find();

        if ($is_blacklist) {
//            if (in_array(1, $is_blacklist)) {
            $results['status'] = false;
//            }
        }
        if ($results['status']) {


            $map['card_id'] = array('in', array_unique($card_id));
            $crm_card_group = M('card_out_crm_groups');
            $card_out_crm_groups = $crm_card_group->where($map)->getField('id,group_id,card_id,group_type');
            $card_idss = array();
            $results['group_types'] = array();
            $results['nas'] = array();
            $results['id'] = array();
            $results['na'] = array();
            $results['card_map_gmr'] = array();
            $results['group_id'] = array();
            $results['card_na'] = array();
            $results['cocg_card_id'] = array();
            foreach ($card_out_crm_groups as $id => $item) {
                $map_gmr = array();
                $group_types = M('group_types');
                $data_sources = D('data_sources');
                $group_type = $item['group_type'];
                $group_id = $item['group_id'];
                $cocg_card_id = $item['card_id'];
                array_push($results['cocg_card_id'], $cocg_card_id);
                $map_group_members_record['data_source_id'] = $group_id;
                $group_members_records = M('group_members_records');

                if ($group_type != 'wai_bu') {
                    $na = $group_members_records->where($map_group_members_record)->getField('table_name');
                    $gmr = $group_members_records->where($map_group_members_record)->getField('table_id', true);
                    if (is_array($gmr)) {
                        if (in_array(null, $gmr)) {
                            $id_array = 1;
                        } else {
                            $id_array = $gmr;
                        }
                    } else {
                        $id_array = 1;
                    }
                    $data_sourcess = $data_sources->relation('group_types')->where('id=' . $group_id)->find();
//                    echo($data_sourcess);
                    if ($data_sourcess && $data_sourcess['group_types']) {
                        $group_type_en_name = $data_sourcess['group_types']['en_name'];
                        if ($group_type_en_name == 'wx_group') {
                            $map_gmr['openid'] = $openid;
                        } elseif ($group_type_en_name == 'CRM_group') {
                            $map_gmr['mobile'] = $tel;
                        } elseif ($group_type_en_name == 'electronic_group') {
                            $map_gmr['tel'] = $tel;
                        }
                    }

                } else {
                    $map_gmr['data_source_id'] = $group_id;
                    $map_gmr['phone_number'] = $tel;
                    $na = 'intermediate_records';
                    $id_array = M('intermediate_records')->where($map_gmr)->getField('id', true);
                }
                array_push($results['group_id'], $group_id);
                array_push($results['card_na'], $na);
                array_push($results['card_map_gmr'], $map_gmr);
//            dump($na);
                if ($map_gmr) {
                    if ($na) {
                        array_push($results['na'], $na);
                        $id = M($na)->where(($map_gmr))->getField('id');
                        array_push($results['id'], $id);

                        if ($id_array == 1) {
                            $id_array = M($na)->where(1)->getField('id', true);
                        }
                        if ($id && in_array($id, $id_array)) {
                            array_push($card_idss, $cocg_card_id);
                        }
                    }
                }
            }
            $results['card_ids'] = array_unique($card_idss);
            if ($results['card_ids']) {
                $card_ids = array_intersect(array_unique($results['card_ids']), $card_id);
                $res_ids = array();
                foreach ($card_ids as $card_id) {
                    array_push($res_ids, $card_id);
                }
                $results['card_ids'] = $res_ids;
                $results['state'] = true;
            }
            $d['openid'] = $openid;
            $common_user_result = M('common_user')->where($d)->find();
            if ($common_user_result) {
//        if ($common_user_result && $common_user_result['phone_number'] != $tel) {
                $common_user_data['phone_number'] = $tel;
                $common_user_data['tel'] = $tel;
                $common_user_data['updated_at'] = format_time();
                $weixin_user_info = M('weixin_user_info')->where($d)->find();
                if (empty($weixin_user_info['nickname'])) {
                    $common_user_data = MergeArray($common_user_data, wei_xin_api()->user($openid));
                }

                M('weixin_user_info')->where($d)->save($common_user_data);
                if (M('common_user')->where($d)->select()) {
                    M('common_user')->where($d)->save($common_user_data);

                } else {
                    $weixin_user_info = M('weixin_user_info')->where($d)->find();

                    $common_user_data['openid'] = $openid;
                    $common_user_data['nickname'] = $weixin_user_info['nickname'];
                    $common_user_data['sex'] = $weixin_user_info['sex'];
                    $common_user_data['headimgurl'] = $weixin_user_info['headimgurl'];
                    add_common_user_message($common_user_data);
//                save_wx_user($openid,$common_user_data);
//                save_wx_user($openid,$common_user_data,'common_user');

                }
            }
        }
    }
    return (($results));
//    return (json_encode($results));

}

//$tag_type:分为两种,"success"或1,为绿色背景,含义为成功;"danger"或"error"或0,为红色背景,含义为失败;
//$tag_delay:分为两种,"human"为人工关闭,有关闭按钮;其他数字为自动关闭,单位为秒;
//$tag_content:为自定义内容,默认类型为字符串,即需要用""括起来.
//$tag_last:cookie存在时间
//定义默认值,content不能有默认值,因为会影响到加载时是否显示警告框
function set_cookies($tag_content, $tag_type = '1', $tag_delay = '3')
{
    cookie_set($tag_type, $tag_delay, $tag_content);
}

function cookie_set($tag_type, $tag_delay, $tag_content, $tag_last = 3)
{
    cookie('tag_type', $tag_type, $tag_last);
    cookie('tag_delay', $tag_delay, $tag_last);
    cookie('tag_content', $tag_content, $tag_last);
}

// $get_page    =   get_page($count,$map);
// $limit       =   $get_page['limit'];
// $page        =   $get_page['show'];
function get_page($count, $map = array(), $nu = 15)
{

    $Page = new \Think\Page($count, $nu);// 实例化分页类 传入总记录数和每页显示的记录数(25)
    foreach ($map as $key => $val) {
        $Page->parameter[$key] = urlencode($val);
    }

    $Page->setConfig('header', '共%TOTAL_ROW%条');
    $Page->setConfig('first', '首页');
    $Page->setConfig('last', '共%TOTAL_PAGE%页');
    $Page->setConfig('prev', '&laquo;');
    $Page->setConfig('next', '&raquo;');

    $Page->setConfig('link', 'indexpagenumb');//pagenumb 会替换成页码
//    $Page -> setConfig('theme','%HEADER% %FIRST% %UP_PAGE% %LINK_PAGE% %DOWN_PAGE% %END%');
//    $Page->setConfig('theme', ' <span class="right">共有<strong>%TOTAL_ROW%</strong>条记录</span> <div class="za">
// <span class="zi">当前第%NOW_PAGE%页</span> <span title="%TOTAL_PAGE%" class="all">共有%TOTAL_PAGE%页</span>
//  <div class="page">%UP_PAGE% %FIRST% %LINK_PAGE% %END% %DOWN_PAGE%</div> </div> ');

    $Page->setConfig('theme', '<div class="page"><ul class="pagination">%UP_PAGE% %FIRST% %LINK_PAGE% %END% %DOWN_PAGE%</ul></div></div> ');


    return array('Page' => $Page, 'limit' => $Page->firstRow . ',' . $Page->listRows, 'show' => $Page->show());
}

// $get_page    =   get_page($count,$map);
// $limit       =   $get_page['limit'];
// $page        =   $get_page['page'];
function get_pages($count, $map = array(), $nu = 15)
{

    $Page = new \Think\Page($count, $nu);// 实例化分页类 传入总记录数和每页显示的记录数(25)
    foreach ($map as $key => $val) {
        $Page->parameter[$key] = urlencode($val);
    }

    $Page->setConfig('header', '共%TOTAL_ROW%条');
    $Page->setConfig('first', '首页');
    $Page->setConfig('last', '共%TOTAL_PAGE%页');
    $Page->setConfig('prev', '上一页');
    $Page->setConfig('next', '下一页');
    $Page->setConfig('link', 'indexpagenumb');//pagenumb 会替换成页码
//    $Page -> setConfig('theme','%HEADER% %FIRST% %UP_PAGE% %LINK_PAGE% %DOWN_PAGE% %END%');
    $Page->setConfig('theme', ' <span class="right">共有<strong>%TOTAL_ROW%</strong>条记录</span> <div class="za">
 <span class="zi">当前第%NOW_PAGE%页</span> <span title="%TOTAL_PAGE%" class="all">共有%TOTAL_PAGE%页</span>
  <div class="page">%UP_PAGE% %FIRST% %LINK_PAGE% %END% %DOWN_PAGE%</div> </div> ');

//    $this->assign('list', $list);// 赋值数据集
//    $this->assign('pagess', $Page->show());// 赋值分页输出

    return array('Page' => $Page, 'limit' => $Page->firstRow . ',' . $Page->listRows, 'show' => $Page->show());
}

function strLength($str, $charset = 'utf-8')
{
    if ($charset == 'utf-8') $str = iconv('utf-8', 'gb2312', $str);
    $num = strlen($str);
    $cnNum = 0;
    for ($i = 0; $i < $num; $i++) {
        if (ord(substr($str, $i + 1, 1)) > 127) {
            $cnNum++;
            $i++;
        }
    }
    $enNum = $num - ($cnNum * 2);
    $number = ($enNum / 2) + $cnNum;
    return ceil($number);
}

//创建微信用户记录
function inster_into_user_message($user)
{
    if (is_array($user)) {
        $user['is_subscribe'] = $user['subscribe'];
        $user['is_blacklist'] = 0;
        $user['state'] = 1;
        $user['updated_at'] = format_time();
        $openid = $user['openid'];
        $common_user_id = M('common_user')->where('openid=' . $openid)->getField('id');
        if (empty($common_user_id)) {
            $common_user_id = add_common_user_message($user);
        }
        $wx_user_info_id = M('weixin_user_info')->where('openid=' . $openid)->getField('id');
        if (empty($wx_user_info_id)) {
            $user['created_at'] = format_time();
            $wx_user_info_id = M('weixin_user_info')->add($user);
        }
        $multi_user_type_associates = M('multi_user_type_associates')->where('wx_user_info_id=' . $wx_user_info_id . 'or common_user_id=' . $common_user_id)->select();
        if (empty($multi_user_type_associates)) {
            $data['created_at'] = format_time();
            $data['updated_at'] = format_time();
            $data['wx_user_info_id'] = $wx_user_info_id;
            $data['common_user_id'] = $common_user_id;
            $data['is_subscribe'] = 1;
            $data['state'] = 1;
            $multi_user_type_associate_id = M('multi_user_type_associates')->add($data);
        } else {
            $de = array('crm_user_id', 'wx_user_info_id', 'common_user_id', 'phone', 'state', 'is_blacklist', 'is_verification', 'is_subscribe', 'created_at', 'updated_at');
            $ds = array();
            foreach ($multi_user_type_associates as $k => $v) {
                foreach ($de as $key) {
                    array_push($ds[$key], $v[$key]);
                }
            }
            $d = array();
            foreach ($de as $key) {
                $d[$key] = array_filter(array_unique($ds[$key]))[0];
            }
            dump(M('multi_user_type_associates')->add($d));

        }
    }
}

function add_common_user_message($user)
{
    $headimgurl = $user['headimgurl'] ? $user['headimgurl'] : '';
    $nickname = $user['nickname'] ? $user['nickname'] : '';
    $tel = $user['tel'] ? $user['tel'] : md5(uniqid());
    $sex = $user['sex'] ? $user['sex'] : 0;

    $udata['openid'] = $user['openid'];

    $udata['username'] = md5(uniqid());
    $udata['tel'] = $tel;
    $udata['email'] = md5(uniqid());

    $udata['nickname'] = $nickname;
    $udata['img'] = $headimgurl;

    $udata['create_time'] = time();
    $udata['create_ip'] = get_client_ip(1);
    $udata['sex'] = $sex;
    $udata['status'] = 1;
    return M('common_user')->add($udata);
}

function getCurMonthFirstDay($date)
{
    return date('Y-m-01 00:00:00', strtotime($date));
}

function getCurMonthLastDay($date)
{
    return date('Y-m-d 23:59:59', strtotime(date('Y-m-01 00:00:00', strtotime($date)) . ' +1 month -1 day'));
}

function get_group_type($ty = false, $tys = false)
{
    $map = array('state' => 1);
    if ($ty != FALSE) {
//        $map = MergeArray($map, array('en_name' => array('neq', 'blacklist_group')));
        $map = MergeArray($map, array('en_name' => array('not in', array('blacklist_group', 'CRM_group'))));
        if ($tys != FALSE) $map = MergeArray($map, array('en_name' => array('NOT IN', array('own_group', 'blacklist_group'))));
    }
    return M('group_types')->where($map)->order('sort_number')->getField('id,name,en_name');
}

function get_source($group_type_id)
{
    return D('group_types')->relation(true)->where(array('id' => $group_type_id))->find()['data_sources'];
//    return M('data_sources')->where('group_type_id='.$group_type_id)->sort('sort_number')->getField('id,name,created_at');

}

/*
* post method
*/
function post($url, $param = array())
{
//    if(!is_array($param)){
//        throw new Exception("参数必须为array");
//    }
    $httph = curl_init($url);
    curl_setopt($httph, CURLOPT_SSL_VERIFYPEER, 0);
    curl_setopt($httph, CURLOPT_SSL_VERIFYHOST, 1);
    curl_setopt($httph, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($httph, CURLOPT_USERAGENT, "Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.0)");
    curl_setopt($httph, CURLOPT_POST, 1);//设置为POST方式
    curl_setopt($httph, CURLOPT_POSTFIELDS, $param);
    curl_setopt($httph, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($httph, CURLOPT_HEADER, 0);
    $rst = curl_exec($httph);
    curl_close($httph);
    $rsts = json_decode(($rst));
    if ($rsts == null) {
        $rsts = $rst;
    }
    dump($rsts);
    return $rsts;
}

function rand_time($nu = 1, $ty = 'month', $st = false)
{
    if ($st == FALSE) {
        return (date("Y-m-d H:i:s", strtotime('-' . randpw($nu, 'N') . ' ' . $ty)));
    } else {
        return (strtotime('-' . randpw($nu, 'N') . ' ' . $ty));

    }

}

function add_wx_user_mu($openid)
{
    $map_wx = array('openid' => $openid, 'is_dianzi' => 1);
    $weixin_user_info = M('weixin_user_info');
    $wx_user_info_id = $weixin_user_info->where($map_wx)->getField('id');
    $f = format_time();

    if ($wx_user_info_id) {
        $weixin_user_info_data['updated_at'] = $f;
        $weixin_user_info_data['is_subscribe'] = 1;
        $weixin_user_info->where($map_wx)->save($weixin_user_info_data);

    } else {
        $user = wei_xin_api()->user($openid);
        if ($user) {
            $weixin_user_info_data = $user;
            $weixin_user_info_data['updated_at'] = $f;
            $weixin_user_info_data['is_subscribe'] = 1;
            $weixin_user_info_data['created_at'] = $f;
            $wx_user_info_id = $weixin_user_info->add($weixin_user_info_data);

        }

    }
    if ($wx_user_info_id) {
        $common_user = M('common_user');
        $common_user = $common_user->where($map_wx)->find();
        $multi_user_type_associates_map1['wx_user_info_id'] = $wx_user_info_id;
        $data = array('wx_user_info_id' => $wx_user_info_id, 'is_subscribe' => 1, 'updated_at' => $f);
        if ($common_user) {
            $common_user_id = $common_user['id'];
            $multi_user_type_associates_map1['common_user_id'] = $common_user_id;
            $multi_user_type_associates_map1['_logic'] = 'OR';
            MergeArray($data, array('common_user_id' => $common_user_id));

        }
        $multi_user_type_associates = M('multi_user_type_associates');
        $multi_user_type_associates_id = $multi_user_type_associates->where($multi_user_type_associates_map1)->getField('id');
        if ($multi_user_type_associates_id) {
            $multi_user_type_associates->where($multi_user_type_associates_map1)->save($data);
        } else {
            $data['state'] = 1;
            $data['sex'] = $common_user['sex'];
            $data['phone'] = $common_user['tel'];
            $data['is_subscribe'] = 1;
            $data['is_blacklist'] = 0;
            $data['is_verification'] = 0;
            $data['created_at'] = $f;
            $multi_user_type_associates->add($data);
        }
        $result = $weixin_user_info->where($map_wx)->find();
    } else {
        $result = array('error_code' => '2', 'error_msg' => '用户查找失败');
    }
    return $result;
}

/*
* post method
*/


function posts($url, $param = array())
{
//    if (!is_array($param)) {
//        throw new Exception("参数必须为array");
//    }
    $httph = curl_init($url);
    curl_setopt($httph, CURLOPT_SSL_VERIFYPEER, 0);
    curl_setopt($httph, CURLOPT_SSL_VERIFYHOST, 1);
    curl_setopt($httph, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($httph, CURLOPT_USERAGENT, "Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.0)");
    curl_setopt($httph, CURLOPT_POST, 1);//设置为POST方式
    curl_setopt($httph, CURLOPT_POSTFIELDS, $param);
    curl_setopt($httph, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($httph, CURLOPT_HEADER, 0);
    $rst = curl_exec($httph);
    curl_close($httph);
//    $rsts = json_decode($rst);
    $rsts = json_decode(htmlspecialchars_decode($rst));

    if ($rsts == null) {
        $rsts = $rst;
    }
    return $rsts;
}

function is_weixin()
{
    if (strpos($_SERVER['HTTP_USER_AGENT'], 'MicroMessenger') !== false) {
        return 1;
    }
    return 0;
}

function is_mobile()
{

    // returns true if one of the specified mobile browsers is detected
    // 如果监测到是指定的浏览器之一则返回true

    $regex_match = "/(nokia|iphone|android|motorola|^mot\-|softbank|foma|docomo|kddi|up\.browser|up\.link|";

    $regex_match .= "htc|dopod|blazer|netfront|helio|hosin|huawei|novarra|CoolPad|webos|techfaith|palmsource|";

    $regex_match .= "blackberry|alcatel|amoi|ktouch|nexian|samsung|^sam\-|s[cg]h|^lge|ericsson|philips|sagem|wellcom|bunjalloo|maui|";

    $regex_match .= "symbian|smartphone|midp|wap|phone|windows ce|iemobile|^spice|^bird|^zte\-|longcos|pantech|gionee|^sie\-|portalmmm|";

    $regex_match .= "jig\s browser|hiptop|^ucweb|^benq|haier|^lct|opera\s*mobi|opera\*mini|320x320|240x320|176x220";

    $regex_match .= ")/i";

    // preg_match()方法功能为匹配字符，既第二个参数所含字符是否包含第一个参数所含字符，包含则返回1既true
    return preg_match($regex_match, strtolower($_SERVER['HTTP_USER_AGENT']));
}



//得到ticket_date
function get_ticket_date($type, $ty = false)
{
    $time = format_time();
    $state = false;
    $data = array();
    $get_ticket = wei_xin_api()->get_js_token($type);
    if ($get_ticket) {
        if ($ty == FALSE) {
            $data['name'] = $type;
            $data['created_at'] = $time;
        }
        $data['updated_at'] = $time;
        $data['code'] = $get_ticket;
        $state = true;
    }
    return array('state' => $state, 'data' => $data);
}

function get_api_ticket($type)
{
    $get_ticket = wei_xin_api()->set_token_arr($type);
    return $get_ticket;

}

function get_active_images()
{
    $active_images = M('active_data')->where(array('state' => 1, 'level' => 1))->getField('id,cn_name,en_name,color', true);
    return $active_images;
}

function upload_excel($info)
{
    // 上传成功
    //要导入的xls文件，位于根目录下的Public文件夹
    $root = $_SERVER['DOCUMENT_ROOT'];
    $filename = dirname($root."Website/Uploads/" . $info['file']['savepath'] . $info['file']['savename']);
//    $filename = "Uploads/" . $info['file']['savepath'] . $info['file']['savename'];
    //导入PHPExcel类库，因为PHPExcel没有用命名空间，只能inport导入
    import("Org.Util.PHPExcel");
    //创建PHPExcel对象，注意，不能少了\
//    $PHPExcel = new \PHPExcel();
    //如果excel文件后缀名为.xls，导入这个类
    import("Org.Util.PHPExcel.Reader.Excel5");
    import("Org.Util.PHPExcel.Reader.Excel2007");

    if ($info['file']['ext'] == 'xlsx') {
        $PHPReader = new \PHPExcel_Reader_Excel2007();
    } else {
        $PHPReader = new \PHPExcel_Reader_Excel5();
    }

//    $PHPReader = new \PHPExcel_Reader_Excel5();
    //载入文件
    $PHPExcel = $PHPReader->load($filename);
    //获取表中的第一个工作表，如果要获取第二个，把0改为1，依次类推
    $currentSheet = $PHPExcel->getSheet(0);
    //获取总列数
    $allColumn = $currentSheet->getHighestColumn();
    //获取总行数
    $allRow = $currentSheet->getHighestRow();
    //循环获取表中的数据，$currentRow表示当前行，从哪行开始读取数据，索引值从0开始
    return ($PHPExcel);
}

function inster_into_crm_date($info, $upload)
{
    if (!$info) {
        // 上传错误提示错误信息
        dump($upload->getError());
    } else {

        $PHPExcel = upload_excel($info);
        //获取表中的第一个工作表，如果要获取第二个，把0改为1，依次类推
        $currentSheet = $PHPExcel->getSheet(0);
        //获取总列数
        $allColumn = $currentSheet->getHighestColumn();
        //获取总行数Application/YiPHP/Controller/MeeZao/Crm/UserMessageController.class.php
        $allRow = $currentSheet->getHighestRow();
        //循环获取表中的数据，$currentRow表示当前行，从哪行开始读取数据，索引值从0开始

        $results = [];
        $result = array('开卡所属' => 'open_card_belongs', '会员卡号' => 'vip_code_id',
            '会员等级' => 'membership_level_id', '身份证号' => 'idcard',
            '姓名' => 'name', '性别' => 'sex', '手机号码' => 'mobile',
            '生日' => 'birthday', '邮箱' => 'email', '开卡日期' => 'open_card_date',
            '通信地址' => 'detail_location', '地址邮编' => 'address_postcode',
            '会员卡生命属性' => 'state'
        );
        $membership_levels = M('membership_levels')->where('state = 1')->getField('cn_name,id');
        $crm_ids = M('customer_relationship_managements')->where(1)->getField('vip_code_id', true);
        $arr = array();
        for ($currentRow = 0; $currentRow <= ($allRow - 1); $currentRow++) {
            $currentRows = $currentRow + 1;
            $i = 0;
            //从哪列开始，A表示第一列
            $t = format_time();
            for ($currentColumn = 'A'; $currentColumn <= $allColumn; $currentColumn++) {
                $address = $currentColumn . $currentRows;
                $row_val = $currentSheet->getCell($address)->getValue();
                if ($currentRow == 0) {
                    array_push($results, $row_val);
                } else {
                    if ($row_val) {
                        $arr[$currentRow - 1]['updated_at'] = $t;
                        $arr[$currentRow - 1]['created_at'] = $t;
                        $arr[$currentRow - 1][$result[$results[$i]]] = $row_val;
                        if ($currentRow != 0 && sizeof($arr[$currentRow - 1]) > 0) {
                            $resul = getIDCardInfo($arr[$currentRow - 1]['idcard']);
                            $sex = get_xingbie_n(($arr[$currentRow - 1]['idcard']));
                            if ($resul['error'] == 2) {
                                $birthday = $resul['birthday'];

                                $arr[$currentRow - 1]['sex'] = $sex;
                                $arr[$currentRow - 1]['birthday'] = $birthday;

                            }
                            if ($membership_levels[$arr[$currentRow - 1]['membership_level_id']]) {
                                $arr[$currentRow - 1]['membership_level_id'] = $membership_levels[$arr[$currentRow - 1]['membership_level_id']];
                            }
                            $arr[$currentRow - 1]['state'] = $arr[$currentRow - 1]['state'] ? $arr[$currentRow - 1]['state'] : 1;
                            $arr[$currentRow - 1]['open_card_date'] = $arr[$currentRow - 1]['open_card_date'] ?
                                $arr[$currentRow - 1]['open_card_date'] : rand_time(2, 'day');

                        }
                    }
                }
                $i += 1;
            }
            if ($currentRow != 0 && in_array($arr[$currentRow - 1]['vip_code_id'], $crm_ids)) {
                $arr[$currentRow - 1] = array();
            }
        }
        $a = array();
        $arr = array_filter($arr);
        foreach ($arr as $k => $v) {
            array_push($a, $v);
        }
//        dump($arr);
        $arr = $a;
        return $arr;

    }
}

//读取excel文件内容 || 适用于部门管理,别的模块未测试  || 王嘉栋
function read_excel_content($info)
{

    $PHPExcel = upload_excel($info); // 载入excel文件
    $sheet = $PHPExcel->getSheet(0); // 读取第一個工作表
    $highestRow = $sheet->getHighestRow(); // 取得总行数
    $highestColumm = $sheet->getHighestColumn(); // 取得总列数

    /** 循环读取每个单元格的数据 */
    $row_array = array();
    for ($row = 1; $row <= $highestRow; $row++) {//行数是以第1行开始
        $col_array = array();
        for ($column = 'A'; $column <= $highestColumm; $column++) {//列数是以A列开始
            array_push($col_array, $sheet->getCell($column . $row)->getValue());
        }
        array_push($row_array, $col_array);
    }
    return $row_array;
}

//excel读取
function excel_read($info, $data_source_id)
{
    $total_number = 0;

    $PHPExcel = upload_excel($info);
    //获取表中的第一个工作表，如果要获取第二个，把0改为1，依次类推
    $currentSheet = $PHPExcel->getSheet(0);
    //获取总列数
    $allColumn = $currentSheet->getHighestColumn();
    //获取总行数
    $allRow = $currentSheet->getHighestRow();
    //循环获取表中的数据，$currentRow表示当前行，从哪行开始读取数据，索引值从0开始


    $each_number = ceil(($allRow - 1) / 5000.0);
    $currentRow = 0;
    $result = array();
    $f = format_time();
    if ($each_number >= 2) {
        for ($i = 0; $i < $each_number; $i++) {
            save_inter_date($currentSheet,  $data_source_id, $f, $allRow, $allColumn, $total_number, $currentRow, $result);
        }
    } else {
        save_inter_date($currentSheet,  $data_source_id, $f, $allRow, $allColumn, $total_number, $currentRow, $result);
    }
    return $total_number;
}


function save_inter_date($currentSheet,  $data_source_id, $f, $allRow, $allColumn, &$total_number, &$currentRow, &$result)
{
    $arr = array();
    for ($j = 0; $j < 5000; $j++) {
        if ($currentRow >= $allRow) {
            break;
        }
        $k = 0;
        $data = array();
        for ($currentColumn = 'A'; $currentColumn <= $allColumn; $currentColumn++) {

            $currentRows = $currentRow + 1;
            $address = $currentColumn . $currentRows;
            if ($currentRow == 0) {
                array_push($result, $currentSheet->getCell($address)->getValue());
            } else {
                $val = $currentSheet->getCell($address)->getValue();
                if ($result[$k] == 'phone_number' && !isTel($val)) {
                    $data = array();
                    break;
                }
                if ($val) $data[$result[$k]] = $val;
            }
            $k++;
        }
        if ($data) {
            $data['updated_at'] = $f;
            $data['created_at'] = $f;
            $data['data_source_id'] = $data_source_id;
            $data['is_activated'] = 0;
            $arr[] = $data;
        }
        $currentRow++;
    }
    if ($arr) {
//        dump($arr);
        $table = D("intermediate_records");
        $table->addAll($arr);
        $total_number += count($arr);
    }
//    return $total_number;
}

//插入记录表

function inster_into_table($messages, $table_name)
{
    $table = M($table_name);
    dump($table->addAll($messages));

//    }

}

function edit_key()
{
    $edit_key = M('sssmall_conf_weixin')->where(true)->getField('edit_key');
    $edit_key = $edit_key ? $edit_key : '574d1892-a9d4-46dd-9446-74030aa8f566';
    return $edit_key;

}

function redir_new_copywriter_url($copywriter_id = false)
{
    $edit_key = M('sssmall_conf_weixin')->where(true)->getField('edit_key');
    if ($edit_key) {
        $url = ('/Copywriter/Copywriter/copywriter_new');
    } else {
        $url = ('/Copywriter/Copywriter/copywriter_news');
    }
    if ($copywriter_id) $url .= '?id=' . $copywriter_id;
    return $url;
}

//创建表

function create_table($create_table)
{
//        dump('aaaa');

//        dump($create_table);
    $sql = '';
    foreach ($create_table as $key => $value) {
//            dump($value);
        if ($key == 'table_name') {
            $sql = $sql . "DROP TABLE IF EXISTS " . $value . ";" . "CREATE TABLE " . $value . " ( `id` int(11)  not  null  auto_increment,";
        } elseif ($key == 'comment') {
            $sql = $sql . "PRIMARY KEY (`id`) ) CHARSET=utf8 comment='" . $value . "';";
        } elseif ($key == 'column') {
            foreach ($value as $k => $v) {
                $default = '';

                if ($v['default'] != FALSE || $v['default'] === 0 || $v['default'] === '0' || $v['default'] === '') {
                    if($v['default'] === ''){
                        $v['default'] = "''";
                    }
                    $default = ' DEFAULT ' . $v['default'];
                }
                if ($v['type'] == 'datetime') {
                    if ($v['null'] != FALSE) {
                        $sql = $sql . $k . " " . $v['type'] . " NOT NULL " . $default;
                    } else {
                        $sql = $sql . $k . " " . $v['type'] . "  " . $default;

                    }
                } elseif ($v['type'] == 'date') {
                    if ($v['null'] != FALSE) {
                        $sql = $sql . $k . " " . $v['type'] . "  NOT NULL " . $default;
                    } else {
                        $sql = $sql . $k . " " . $v['type'] . "  " . $default;
                    }
                } else {
                    $length = '';
                    if ($v['length']) {
                        $length = '(' . $v['length'] . ')';
                    }
                    if ($v['null'] != FALSE) {
                        $sql = $sql . $k . " " . $v['type'] . $length . "  NOT NULL " . $default;
                    } else {
                        $sql = $sql . $k . " " . $v['type'] . $length . $default;
                    }
                }
                $sql = $sql . " COMMENT '" . $v['comment'] . "', ";

            }
        }

    }

    dump($sql);

    $c = M()->execute($sql);
    dump($c);
}

function aass()
{
    return 'aass';
}

/*
        *功能：php完美实现下载远程图片保存到本地
        *参数：文件url,保存文件目录,保存文件名称，使用的下载方式
        *当保存文件名称为空时则使用远程文件原来的名称
        */
function getImage($url, $save_dir = '', $filename = '', $type = 0)
{
    if (trim($url) == '') {
        return array('file_name' => '', 'save_path' => '', 'error' => 1);
    }
    if (trim($save_dir) == '') {
        $save_dir = './';
    }
    if (trim($filename) == '') {//保存文件名
        $ext1 = explode('.', $url)[1];
        $ext2 = explode('=', $url)[1];
        if (!($ext1 != 'gif' && $ext1 != 'jpg' && $ext1 != 'png' && $ext1 != 'jpeg')) {
            $ext = $ext1;
        } elseif (!($ext2 != 'gif' && $ext2 != 'jpg' && $ext2 != 'png' && $ext2 != 'jpeg')) {
            $ext = $ext2;
        } else {
            $ext = 'jpg';
        }
        $filename = time() . rand(0, 10000) . '.' . $ext;
    }

    if (0 !== strrpos($save_dir, '/')) {
        $save_dir .= '/';
    }
    //创建保存目录
    if (!file_exists($save_dir) && !mkdirs($save_dir)) {
        return array('file_name' => '', 'save_path' => '', 'error' => 5);
    } else {

        //获取远程文件所采用的方法
        if ($type) {
            $ch = curl_init();
            $timeout = 5;
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
            $img = curl_exec($ch);
            curl_close($ch);
        } else {
            ob_start();
            readfile($url);
            $img = ob_get_contents();
            ob_end_clean();
        }
        //$size=strlen($img);
        //文件大小
        $fp2 = @fopen($save_dir . $filename, 'a');
        fwrite($fp2, $img);
        fclose($fp2);
        unset($img, $url);
        if (file_exists_case($save_dir . $filename)) {
            $result = array('file_name' => $filename, 'save_path' => $save_dir . $filename, 'error' => 0);
        } else {
            $result = array('error' => 1);
        }
        return $result;
    }
}

/*
 * 将多维数组转为一维数组
 * @author Jiera
 * @param $array 传入的多维数组值
 * */
function array_multi2single($array)
{
    static $result_array = array();
    foreach ($array as $key => $value) {
        if (is_array($value)) {
            array_multi2single($value);
        } else
            $result_array[$key] = $value;
    }
    return $result_array;
}

/**
 * 功能:解压zip文件
 * 参数:
 * @param $file_name   需要解压的文件的路径
 * @param $destination   解压之后存放的路径
 * 需要使用 ZZIPlib library ，请确认该扩展已经开启
 */
function unzip($file_name, $destination)
{
    // 实例化对象
    $zip = new ZipArchive();
    //打开zip文档，如果打开失败返回提示信息
    $zip_open = $zip->open($file_name);
    if ($zip_open !== TRUE) {
        die ("Could not open archive");
    }
    //将压缩文件解压到指定的目录下
    $zip->extractTo($destination);
    $common = $zip->getCommentName($destination);
    //关闭zip文档
    $zip->close();
    echo 'Archive extracted to directory';
    return $common;
}


/**
 * 功能:删除指定目录下的文件/文件夹
 * 参数:
 * @param $dir  你要删除的目录路径
 */
function rmdirs($dir)
{
    error_reporting(0);    //函数会返回一个状态,我用error_reporting(0)屏蔽掉输出
    //rmdir函数会返回一个状态,我用@屏蔽掉输出
    $dir_arr = scandir($dir);
    foreach ($dir_arr as $key => $val) {
        if ($val == '.' || $val == '..') {
        } else {
            if (is_dir($dir . '/' . $val)) {
                if (@rmdir($dir . '/' . $val) == 'true') {
                }    //去掉@您看看
                else
                    rmdirs($dir . '/' . $val);
            } else
                unlink($dir . '/' . $val);
        }
    }
}

/**
 * 功能:php读取指定目录文件夹下所有文件名
 * 参数:
 * @param $path 指定的目录
 */
function get_all_dirname($path)
{
    $dir = $path;
    //PHP遍历文件夹下所有文件
    $handle = opendir($dir . ".");
    //定义用于存储文件名的数组
    $array_file = array();
    while (false !== ($file = readdir($handle))) {
        if ($file != "." && $file != "..") {
            $array_file[] = $file; //输出文件名
        }
    }
    closedir($handle);
    return $array_file;
}

/*
 * 作者：周振
 * 功能：加载上传的Excel文件，根据不同的文件类型，加载不同模块，返回相关的文件配置信息
 * 参数：{
 * filename: '完整的文件路径',
 * sheet_index: 0 //指定活动的sheet
 * }
 * 返回：{
 * PHPExcel: PHPExcel, //Class对象
 * currentSheet: 0 , //指定的活动sheet的索引
 * allRow: 100, //指定sheet下，总共的行数
 * allColumn: 100 //指定sheet下，总共的列数
 * }
 * */
function loadExcelInfo($data)
{
    //要导入的xls文件，位于根目录下的Public文件夹
    $filename = $data["filename"];

    $sheet_index = $data["sheet_index"];
    if (empty($sheet_index)) {
        $sheet_index = 0;
    }

    //导入PHPExcel类库，因为PHPExcel没有用命名空间，只能inport导入
    import("Org.Util.PHPExcel");

    //创建PHPExcel对象，注意，不能少了\
    $PHPExcel = new \PHPExcel();

    //如果excel文件后缀名为.xls，导入这个类
//        dump((($filename)));
//        dump((get_extension($filename)));
//        dump(strtolower(get_extension($filename)));
//        dump(strtolower(get_extension($filename)) == 'xlsx');
//die;
    if (strtolower(get_extension($filename)) == 'xlsx') {
        import("Org.Util.PHPExcel.Reader.Excel2007");
        $PHPReader = new \PHPExcel_Reader_Excel2007();
    } else {
        import("Org.Util.PHPExcel.Reader.Excel5");
        $PHPReader = new \PHPExcel_Reader_Excel5();
    }

    //载入文件
    $PHPExcel = $PHPReader->load($filename);

    //获取表中的第一个工作表，如果要获取第二个，把0改为1，依次类推
    $currentSheet = $PHPExcel->getSheet($sheet_index);

    //获取总列数
    $allColumn = $currentSheet->getHighestColumn();

    //获取总行数
    $allRow = $currentSheet->getHighestRow();

    return array("PHPExcel" => $PHPExcel, "currentSheet" => $currentSheet, "allColumn" => $allColumn, "allRow" => $allRow);
}

/*
 * 作者： 周振
 * 功能： 读取上传文件的内容
 * 参数：{
 * filename = '/var/www/meezao_market/Website/test1.xlsx', //上传文件的绝对路径，必填
 * sheet_index: '', //指定excel的活动sheet，选填
 * }
 * 返回：{
 * [[1][2][3]]
 * }
 * */
function readExcelData($data)
{
    $result = array();
    $row_array = array();

    $excel_data_info = loadExcelInfo($data);
//        dump($excel_data_info);
    $PHPExcel = $excel_data_info["PHPExcel"];
    $currentSheet = $excel_data_info["currentSheet"];
    $allRow = $excel_data_info["allRow"];
    $allColumn = $excel_data_info["allColumn"];

    for ($currentRow = 1; $currentRow <= $allRow; $currentRow++) {
        //从哪列开始，A表示第一列
        $col_array = array();
        for ($currentColumn = "A"; $currentColumn <= $allColumn; $currentColumn++) {

//                $currentColumn = PHPExcel_Cell::stringFromColumnIndex($i);

            $address = $currentColumn . $currentRow;
            $row_val = $currentSheet->getCell($address)->getValue();

            if ($currentRow == 0) {
                array_push($results, $row_val);
            } else {
                array_push($col_array, $row_val);
            }
        }
        array_push($row_array, $col_array);
    }
    /*
      $excel_array = array();
      $result_array = array_filter($row_array);
      foreach ($result_array as $k => $v) {
          array_push($excel_array, $v);
      }
      dump($result_array);
      */
    return $row_array;
}


/*
 * 功能： 初始化PHPExcel对象
 * @param $excel_info //导出Excel数据的基本配置信息, 选填项，可空。
 * {
 * Creator: '',
 * LastModifiedBy: '',
 * Title: '',
 * Subject: '',
 * Description: '',
 * Keywords: '',
 * Category: ''
 * }
 * @return PHPExcel对象
 * */
function getExcelObj($excel_info = '')
{
    import("Org.Util.PHPExcel");
    $objPHPExcel = new \PHPExcel();

    if (!empty($excel_info)) {
        $Creator = (empty($excel_info["Creator"]) ? "Meezao" : $excel_info["Creator"]);
        $LastModifiedBy = (empty($excel_info["LastModifiedBy"]) ? "JYZX" : $excel_info["LastModifiedBy"]);
        $Title = (empty($excel_info["Title"]) ? "JYZX" : $excel_info["Title"]);
        $Subject = (empty($excel_info["Subject"]) ? "Meezao" : $excel_info["Subject"]);
        $Description = (empty($excel_info["Description"]) ? "Meezao" : $excel_info["Description"]);
        $Keywords = (empty($excel_info["Keywords"]) ? "Meezao" : $excel_info["Keywords"]);
        $Category = (empty($excel_info["Category"]) ? "Meezao" : $excel_info["Category"]);

        $objPHPExcel->getProperties()->setCreator($Creator)
            ->setLastModifiedBy($LastModifiedBy)
            ->setTitle($Title)
            ->setSubject($Subject)
            ->setDescription($Description)
            ->setKeywords($Keywords)
            ->setCategory($Category);
    }
    return $objPHPExcel;
}

/*
 * 功能： 通过PHPExcel对象，配置活动sheet
 * @param $excel_info //导出Excel数据的基本配置信息
 * @param $sheet_index //指定当前活动的sheet
 * @return PHPExcel对象
 * */
function setSheetObj($objPHPExcel, $sheet_index = 0)
{
    $objPHPExcel->setActiveSheetIndex($sheet_index);
    return $objPHPExcel;
}

/*
 * 功能： 将二维数据写入到Excel文件中
 * @param $excel_info //导出Excel数据的基本配置信息
 * @param $sheet_index //指定当前活动的sheet
 * @return PHPExcel对象
 * */
function setSheetData($data_info, $excel_info = '')
{
    import("Org.Util.PHPExcel.IOFactory");

    //基本二维数据
    $data = $data_info["data"];

    //下载的文件名
    $filename = $data_info["filename"];

    //文件扩展名
    //$extname = $data_info["extname"];

    //设置活动的sheet
    //$sheet_index = $data_info["sheet_index"];

    $objPHPExcel = getExcelObj($excel_info);
    $objPHPExcel = setSheetObj($objPHPExcel);

    for ($i = 0; $i < count($data); $i++) {
        if ($i == 0) {
            foreach ($data[$i] as $key => $value) {
                $cwr = chr(65 + $key) . '1';//这里用来输出ABCDEFG...
                $objPHPExcel->getActiveSheet()->SetCellValue($cwr, $value);
            }
        } else {
            foreach ($data[$i] as $key => $value) {
                $cwr = chr(65 + $key) . ($i + 1);
                $objPHPExcel->getActiveSheet()->SetCellValue($cwr, $value);
            }
        }
    }

    header('Content-Type: application/vnd.ms-excel');
    header("Content-Disposition: attachment;filename=\"$filename\"");
    header('Cache-Control: max-age=0');

    $objWriter = \PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
//        $objWriter->save($filename);
    $objWriter->save('php://output');
    exit;
}


/*
 * 作者： 周振
 * 功能： 求中英混合的字符串的长度
 * 参数： @params string
 * 返回： int
 * */
function utf8_strlen($string = null)
{
    // one:
    /*
     * $tmp = iconv('GB2312', 'UTF-8', $string);
     * if(!empty($tmp)){
     *   $string = $tmp;
     * }
     * preg_match_all('/./us', $string, $match);
     * $result = count($match[0]);
     */
    // two:
    $result = iconv_strlen($string, "UTF-8");
    return $result;
}

/*
 * 作者： 周振
 * 功能： 两数相除，求其整数和余数
 * 参数： @params dividend 被除数， divisor 除数
 * 返回： integer integer_number整数 ，  integer remainder_number 余数
 * */
function get_integer_remainder($dividend, $divisor)
{
    //整数部分：
    $result["integer_number"] = intval(floor($dividend / $divisor));
    //余数部分：
    $result["remainder_number"] = $dividend % $divisor;
    return $result;
}

/*
 * 作者： 周振
 * 功能： 获取系统当前时间
 * 参数：
 * 返回： string datetime
 * */
function get_system_time()
{
    return date("Y-m-d H:i:s", time());
}


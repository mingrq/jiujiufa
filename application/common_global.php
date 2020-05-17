<?php
/**
 * Created by PhpStorm.
 * User: ming
 * Date: 2020/5/3
 * Time: 0:08
 */


//定义文件路径常量
//---获取URL访问的ROOT地址 网站的相对路径
define('BASE_SITE_ROOT', str_replace('/index.php', '', \think\Request::instance()->root()));
//---管理员后台资源路径
define('ADMIN_SITE_ROOT', BASE_SITE_ROOT.'/static/admin');
//---前端资源路径
define('INDEX_SITE_ROOT', BASE_SITE_ROOT.'/static/index');
//---公用资源路径
define('COMMON_SITE_ROOT', BASE_SITE_ROOT.'/static/common');

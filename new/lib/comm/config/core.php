<?php
//定义一些常用的基类短语
//require_once('config/config.cache.php');
//require_once('config/config.queue.php');

//定义缓存类型，目前使用 Memcache类
define('_CACHE_MEMCACHE', 'Memcache');  //类文件名相同
define('_CACHE_MEMCACHED', 'Memcached');
define('_CACHE_REDIS', 'Redis');
define('_CACHE_FILE', 'File');
define('_CACHE_PARAM_DEFAULT',	'DEFAULT'); //默认缓存配置
//定义缓存类的初始参数
//缺省过期时间1个月
if(!defined('CACHE_DEFAULT_EXP_TIME')){
    define("CACHE_DEFAULT_EXP_TIME",    2592000);
}


//定义队列类型，默认使用 Httpsqs
define('_QUEUE_HTTPSQS', 'Httpsqs');    //类文件名相同
define('_QUEUE_REDIS', 'Redis');
define('_QUEUE_RABBITMQ', 'Rabbitmq');
define('_QUEUE_PARAM_DEFAULT', 'DEFAULT'); //默认缓存配置



//数据库
$_CORE_FAV_LIB['db']	= array('PATH'=>'comm/db/DBSource.php', 'CLASS'=>'DBSource', 'DEF_ARG'=>'');
//smarty模板 
$_CORE_FAV_LIB['view'] 	= array('PATH'=>'comm/template/Template.php', 'CLASS'=>'Template', 'DEF_ARG'=>'');
//Memcache 内存服务器
$_CORE_FAV_LIB['cache']= array('PATH'=>'comm/cache/Cache.php','CLASS'=>'Cache', 'DEF_ARG'=>'');
//Queue 队列服务器
$_CORE_FAV_LIB['queue']= array('PATH'=>'comm/queue/Queue.php','CLASS'=>'Queue', 'DEF_ARG'=>'');
//request 对像
$_CORE_FAV_LIB['request']= array('PATH'=>'comm/request/Request.php','CLASS'=>'Request', 'DEF_ARG'=>'');


?>
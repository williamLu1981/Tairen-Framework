<?php
/**
 * Created by PhpStorm.
 * User: williamlu
 * Date: 14-8-27
 * Time: 下午3:00
 *
 * Tairen-Framwork 入口文件
 * @version 1.0
 */
//WEB调试开关
defined('__DEBUG') or define('__DEBUG', true);
//DB调试开关
defined('__DB_DEBUG') or define('__DB_DEBUG', true);

//定义缓存开关 true/false， 不定义则默认开启 true
defined('_CACHE_SWITCH') or define('_CACHE_SWITCH', true);
//定义默认的缓存服务
defined('_CACHE_DEFAULT') or define('_CACHE_DEFAULT', 'Memcache');   //Memcache; Memcached; Redis; File;
//设置memcache为一致性hash
defined('_CACHE_MEMCACHE_CONSISTENT') or define('_CACHE_MEMCACHE_CONSISTENT', true);

//定义默认的队列服务
defined('_QUEUE_DEFAULT') or define('_QUEUE_DEFAULT', 'Httpsqs');   //Httpsqs; Redis; Rabbitmq;

//定义项目根目录
define('_APP_ROOT' , dirname(__FILE__)."/");
//包含tieyou框架引导文件
require_once(dirname(dirname(__FILE__)) . '/lib/core.php');
//包含核心配置文件
require_once(dirname(__FILE__).'/config/main.php');


Core::Singleton("comm.application.WebApplication")->run();



?>

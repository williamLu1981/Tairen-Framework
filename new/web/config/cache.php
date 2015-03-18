<?php
/**
 * Created by PhpStorm.
 * User: williamlu
 * Date: 14-8-25
 * Time: 下午4:12
 *
 * 缓存配置方案
 * @version 1.0
 */

//define('_CACHE_PARAM_DEFAULT',	'DEFAULT'); //默认缓存配置

//定义缓存类的初始参数,缺省过期时间1个月
define("CACHE_DEFAULT_EXP_TIME", 2592000);

$GLOBALS['MEMCACHE_CACHE_CONFIG'] = array(
    //缺省集群
    'DEFAULT' => array(
        array('HOST' => '172.16.48.131', 'PORT' => 11211),
    ),
    //自定义集群
    'TEST' => array(
        array('HOST' => '172.16.48.131', 'PORT' => 11212),
    ),

);


$GLOBALS['MEMCACHED_CACHE_CONFIG'] = array(
    //缺省集群
    'DEFAULT' => array(
        array('HOST' => '172.16.48.131', 'PORT' => 11211, 'WEIGHT' => 20),
        array('HOST' => '172.16.48.131', 'PORT' => 11212, 'WEIGHT' => 20),
    ),
    //自定义集群
    'TEST' => array(
        array('HOST' => '172.16.48.131', 'PORT' => 11211, 'WEIGHT' => 20),
        array('HOST' => '172.16.48.131', 'PORT' => 11212, 'WEIGHT' => 20),
    ),

);


$GLOBALS['REDIS_CACHE_CONFIG'] = array(
    //缺省服务器
    'DEFAULT' => array(
        'MASTER' => array(
            array('HOST' => '172.16.48.131', 'PORT' => 6385),
        ),
        'SLAVE' => array(
            array('HOST' => '172.16.48.131', 'PORT' => 6385),
        ),
    ),
    //自定义集群
    'TEST' => array(
        'MASTER' => array(
            array('HOST' => '172.16.48.131', 'PORT' => 6386),
        ),
        'SLAVE' => array(
            array('HOST' => '172.16.48.131', 'PORT' => 6386),
        ),
    ),
);

$GLOBALS['FILE_CACHE_CONFIG'] = array(
    //缺省集群
    'DEFAULT' => '/tmp/filecache1',
    //自定义集群
    'TEST' => '/tmp/filecache2',

);

?>
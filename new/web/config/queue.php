<?php
/**
 * Created by PhpStorm.
 * User: williamlu
 * Date: 14-8-11
 * Time: 下午1:30
 *
 * Queue配置方案
 */


//define('_QUEUE_PARAM_DEFAULT', 'DEFAULT'); //默认缓存配置


$GLOBALS['HTTPSQS_QUEUE_CONFIG'] = array(
    //缺省配置
    'DEFAULT' => array(
        'DEFAULT_QUEUE' => array('HOST' => '172.16.48.131', 'PORT' => 1218, 'AUTH' => '', 'CHARSET' => 'UTF-8'),
    ),
    //自定义配置
    'TEST' => array(
        'TEST_QUEUE' => array('HOST' => '172.16.48.131', 'PORT' => 1219, 'AUTH' => '', 'CHARSET' => 'UTF-8'),
    ),

);

$GLOBALS['REDIS_QUEUE_CONFIG'] = array(
    //缺省服务器
    'DEFAULT' => array(
        'MASTER' => array(
            'DEFAULT_QUEUE' => array('HOST' => '172.16.48.131', 'PORT' => 6385),
        ),
        'SLAVE' => array(
            'DEFAULT_QUEUE' => array('HOST' => '172.16.48.131', 'PORT' => 6385),
        ),
    ),
    //自定义集群
    'TEST' => array(
        'MASTER' => array(
            'TEST_QUEUE' => array('HOST' => '172.16.48.131', 'PORT' => 6386),
        ),
        'SLAVE' => array(
            'TEST_QUEUE' => array('HOST' => '172.16.48.131', 'PORT' => 6386),
        ),
    ),
);

$GLOBALS['RABBITMQ_QUEUE_CONFIG'] = array(
    //缺省配置
    'DEFAULT' => array(
        'DEFAULT_QUEUE' => array('HOST' => '172.16.48.131', 'PORT' => 5672, 'VHOST' => 'vh_ty_gonggong', 'USR' => 'u_tygonggong', 'PWD' => 'u_tygonggong', 'READTIMEOUT' => 0.2, 'WRITETIMEOUT' => 0.2, 'CONNECTTIMEOUT' => 0.2), //172.22.128.205 FAT
    ),
    //自定义配置
    'TEST' => array(
        'TEST_QUEUE' => array('HOST' => '172.16.48.131', 'PORT' => 5673, 'VHOST' => 'vh_ty_gonggong', 'USR' => 'u_tygonggong', 'PWD' => 'u_tygonggong', 'READTIMEOUT' => 0.2, 'WRITETIMEOUT' => 0.2, 'CONNECTTIMEOUT' => 0.2),
    ),

);


?>
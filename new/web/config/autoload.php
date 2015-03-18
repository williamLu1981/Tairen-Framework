<?php
/**
 * Created by PhpStorm.
 * User: williamlu
 * Date: 14-8-25
 * Time: 下午12:11
 *
 * autoload配置文件
 * @version 1.0
 */

// 防止重复include本文件
if(!defined('CONFIG_AUTOLOAD_INCLUDED')){
    define('CONFIG_AUTOLOAD_INCLUDED',1);


    $GLOBALS['AUTOLOAD'] = array(

        //开发自定义
        'User' => 'user/User.php',
    );

}
<?php
/**
 * Created by PhpStorm.
 * User: williamlu
 * Date: 14-8-25
 * Time: 下午4:12
 *
 * 全站数据库连接配置文件
 * @version 1.0
 */
// 防止重复include本文件
if(!defined('CONFIG_DB_INCLUDED')){
    define('CONFIG_DB_INCLUDED',1);

    // =========================== USER 组 =========================================
    $GLOBALS['INIT_MASTER']= array('HOST'=>'172.16.48.131','PORT'=>'3306','USER'=>'williamLu','PASS'=>'bebo!@#','NAME'=>'db_tieyou_init');
    $GLOBALS['INIT_SLAVE']	= array('HOST'=>'172.16.48.131','PORT'=>'3306','USER'=>'williamLu','PASS'=>'bebo!@#','NAME'=>'db_tieyou_init');
}
?>
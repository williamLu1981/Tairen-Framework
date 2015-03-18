<?php
/**
 * Created by PhpStorm.
 * User: williamlu
 * Date: 14-8-26
 * Time: 下午7:02
 *
 * Tieyou框架引导文件
 * @version 1.0
 */

//定义windows和Linux系统的路径链接符号差别
defined('_PATH_SEPARATOR') or define('_PATH_SEPARATOR', preg_match("/WIN/i", PHP_OS) ? ";" : ":");
//定义windows和Linux系统的路径链接符号差别
defined('_OS') or define('_OS', preg_match("/WIN/i", PHP_OS) ? "WIN" : "UNIX");

//定义框架根目录
define('_LIB_ROOT' , dirname(__FILE__)."/");

ini_set("include_path", "."._PATH_SEPARATOR._LIB_ROOT._PATH_SEPARATOR._APP_ROOT);

//@chdir(dirname(__FILE__));
//$_SERVER['SCRIPT_FILENAME'] = __FILE__;


//包含核心类库
require_once(_LIB_ROOT . 'comm/core/Core.php');
//包含安全过滤
require_once(_LIB_ROOT . 'comm/security/secFilter.php');
?>

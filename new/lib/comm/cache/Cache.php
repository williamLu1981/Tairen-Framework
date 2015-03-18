<?php
/**
 * Created by PhpStorm.
 * User: williamlu
 * Date: 14-8-4
 * Time: 下午20:15
 *
 * 缓存类
 * @version 1.0
 */

//定义当前目录常量
if (_OS == 'WIN') {

    define('_CACHE_CLASS_PATH', dirname(__FILE__) . '\\');
} else {

    define('_CACHE_CLASS_PATH', dirname(__FILE__) . '/');
}

//定义缓存开关 true/false, 默认开启true
defined('_CACHE_SWITCH') or define('_CACHE_SWITCH', true);

//包含公用接口文件
require_once( _CACHE_CLASS_PATH . 'interface.cache.php');
require_once( _CACHE_CLASS_PATH . 'abstract.cache.php');


class Cache {
    /**
     * 获取实例并初始化对像
     *
     * @param string $setting   缓存分组名
     * @param null $plugName 缓存插件名
     * @return bool|void
     */
    public static function factory($setting = '', $plugName = null) {

        //判断是否调用系统配置
        if (empty($plugName)) {

            if (_CACHE_DEFAULT == _CACHE_MEMCACHE || _CACHE_DEFAULT == _CACHE_MEMCACHED || _CACHE_DEFAULT == _CACHE_REDIS || _CACHE_DEFAULT == _CACHE_FILE) {

                $plugName = _CACHE_DEFAULT;
            } else {

                $plugName = _CACHE_MEMCACHE;
            }
        }

        $setting = strtoupper($setting);
        $plugNameUpper = strtoupper($plugName);

        if (!empty($setting) && isset($GLOBALS[$plugNameUpper.'_CACHE_CONFIG'][$setting])) {

            $setting = $GLOBALS[$plugNameUpper.'_CACHE_CONFIG'][$setting];
        } else {

            $setting = $GLOBALS[$plugNameUpper.'_CACHE_CONFIG'][_CACHE_PARAM_DEFAULT];
        }

        $sKey = self::getSKey($plugName, $setting);

        if (core::registry($sKey)) {

            return core::register($sKey);
        } else {

            $obj = self::instance($plugName);
            $obj->init($setting);
            core::register($sKey, $obj);
            return $obj;
        }
    }


    /**
     * 通过插件名和参数组成唯一识别字串
     *
     * @param String $plugName 插件名
     * @param Mixed $setting 参数
     * @return String
     */
    private static function getSKey($plugName, $setting) {

        if (is_array($setting)) {
            $setStr = serialize($setting);
        } else if (is_object($setting)) {
            //如$setting有 private 的变量，则系统会出现问题
            $setStr = serialize($setting);
        } else {
            $setStr = strval($setting);
        }


        return md5($setStr . $plugName);
    }


    /**
     * 通过插件名获取对像
     *
     * @param $plugName 插件名(例如memcache)
     * @return mixed
     * @throws Exception
     */
    private static function instance($plugName) {

        if (empty($plugName)) {

            if (_CACHE_DEFAULT == _CACHE_MEMCACHE || _CACHE_DEFAULT == _CACHE_MEMCACHED || _CACHE_DEFAULT == _CACHE_REDIS || _CACHE_DEFAULT == _CACHE_FILE) {

                $plugName = _CACHE_DEFAULT;
            } else {

                $plugName = _CACHE_MEMCACHE;
            }
        }

        $plugFileName = self::getFileName($plugName);
        $plugClass = self::getClassName($plugName);

        if (file_exists($plugFileName)) {

            require_once($plugFileName);

            if (class_exists($plugClass)) {

                $obj = new $plugClass;
                return $obj;
            } else {

                throw new Exception("ICache:: class $plugClass not found in PHP file $plugFileName .");
            }
        } else {

            throw new Exception("Cache:: not found cache plugin file $plugFileName .");
        }
    }

    /**
     * 获取插件文件名
     *
     * @param String $plugName 插件名
     * @return String
     */
    private static function getFileName($plugName) {

        if (_OS == 'WIN') {

            return sprintf('%s\plug\%s.php', _CACHE_CLASS_PATH, $plugName);
        } else {

            return sprintf('%s/plug/%s.php', _CACHE_CLASS_PATH, $plugName);
        }

    }

    /**
     * 获取插件类名
     *
     * @param String $plugName 插件名
     * @return void
     */
    private static function getClassName($plugName) {

        return sprintf("%s_cache", $plugName);
    }
}

?>
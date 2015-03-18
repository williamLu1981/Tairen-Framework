<?php
/**
 * Created by PhpStorm.
 * User: williamlu
 * Date: 14-8-11
 * Time: 下午4:05
 *
 * 队列类
 * @version 1.0
 */


//定义当前目录常量
if (_OS == 'WIN') {

    define('_QUEUE_CLASS_PATH', dirname(__FILE__) . '\\');
} else {

    define('_QUEUE_CLASS_PATH', dirname(__FILE__) . '/');
}
//包含公用接口文件
require_once( _QUEUE_CLASS_PATH . 'interface.queue.php');
require_once( _QUEUE_CLASS_PATH . 'abstract.queue.php');


class Queue {
    /**
     * 获取实例并初始化对像
     *
     * @param string $setting   分组名
     * @param null $plugName 插件名
     * @return bool|void
     */
    public static function factory($setting = '', $plugName = null) {

        //判断是否调用系统配置
        if (empty($plugName)) {

            if (_QUEUE_DEFAULT == _QUEUE_HTTPSQS || _QUEUE_DEFAULT == _QUEUE_REDIS || _QUEUE_DEFAULT == _QUEUE_RABBITMQ) {

                $plugName = _QUEUE_DEFAULT;
            } else {

                $plugName = _QUEUE_HTTPSQS;
            }
        }

        $setting = strtoupper($setting);
        $plugNameUpper = strtoupper($plugName);


        if (!empty($setting) && isset($GLOBALS[$plugNameUpper.'_QUEUE_CONFIG'][$setting])) {
            $setting = $GLOBALS[$plugNameUpper.'_QUEUE_CONFIG'][$setting];
        } else {

            $setting = $GLOBALS[$plugNameUpper.'_QUEUE_CONFIG'][_QUEUE_PARAM_DEFAULT];
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

            if (_QUEUE_DEFAULT == _QUEUE_HTTPSQS || _QUEUE_DEFAULT == _QUEUE_REDIS || _QUEUE_DEFAULT == _QUEUE_RABBITMQ) {

                $plugName = _QUEUE_DEFAULT;
            } else {

                $plugName = _QUEUE_HTTPSQS;
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

                throw new Exception("IQueue:: class $plugClass not found in PHP file $plugFileName .");
            }
        } else {

            throw new Exception("Queue:: not found queue plugin file $plugFileName .");
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

            return sprintf('%s\plug\%s.php', _QUEUE_CLASS_PATH, $plugName);
        } else {

            return sprintf('%s/plug/%s.php', _QUEUE_CLASS_PATH, $plugName);
        }
    }

    /**
     * 获取插件类名
     *
     * @param String $plugName 插件名
     * @return void
     */
    private static function getClassName($plugName) {

        return sprintf("%s_queue", $plugName);
    }
}

?>
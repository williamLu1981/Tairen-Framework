<?php
/**
 * Created by PhpStorm.
 * User: williamlu
 * Date: 14-8-5
 * Time: 上午11:40
 *
 * Memecache缓存类
 * @version 1.0
 */

class Memcached_cache extends abstract_cache {

    /**
     * Memcache 对像
     * @var Object
     */
    private $objMemcached = null;

    /**
     * 析构
     */
    public function __construct() {

        $this->objMemcached = new Memcached();

        if (_CACHE_MEMCACHE_CONSISTENT === true) {

            //修正memcache为一致性hash
            $this->objMemcached->setOption(Memcached::OPT_DISTRIBUTION, Memcached::DISTRIBUTION_CONSISTENT);
        }

    }


    /**
     * 初始化对像
     *
     * @param mixed $arrConfig
     */
    public function init($arrConfig) {

        if (is_array($arrConfig)) {

            $this->objMemcached->addServers($arrConfig);
        }
    }


    /**
     * 获取数据
     *
     * @param 键值 $key
     * @param callable $cache_cb
     * @param null $cas_token
     * @return Mixed|null
     */
    public function get($key, callable $cache_cb = null, &$cas_token = null) {

        if (_CACHE_SWITCH == true) {

            return $this->objMemcached->get($key, $cache_cb, $cas_token);
        } else {

            return null;
        }

    }

    /**
     * 设置缓存数据
     *
     * @param String $key 键值
     * @param Mixed $value 要缓存的内容
     * @param Integer $ttl 存活时间
     * @return bool|void
     */
    public function set($key, $value, $ttl = CACHE_DEFAULT_EXP_TIME) {

        if (_CACHE_SWITCH == true) {

            return $this->objMemcached->set($key, $value, $ttl);
        } else {

            return false;
        }


    }


    /**
     * 删除指定Key的内容
     *
     * @param String $key
     * @param int $time
     * @return bool|void
     */
    public function delete($key, $time = 0) {

        if (_CACHE_SWITCH == true) {

            return $this->objMemcached->delete($key, $time);
        } else {

            return false;
        }

    }

    /**
     * 其他调用直接传递给memcached对象执行
     *
     * @param $function
     * @param $arrArguments
     * @return mixed
     */
    public function __call($function, $arrArguments) {

        $intTmp = count($arrArguments);

        switch ($intTmp) {
            case 1:
                return $this->objMemcached->$function($arrArguments[0]);
            case 2:
                return $this->objMemcached->$function($arrArguments[0], $arrArguments[1]);
            case 3:
                return $this->objMemcached->$function($arrArguments[0], $arrArguments[1], $arrArguments[2]);
            case 4:
                return $this->objMemcached->$function($arrArguments[0], $arrArguments[1], $arrArguments[2], $arrArguments[3]);
            default:
                return false;

        }
    }

}

?>
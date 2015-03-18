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

class Memcache_cache extends abstract_cache {

    /**
     * Memcache 对像
     * @var Object
     */
    private $objMemcache = null;

    /**
     * 析构
     */
    public function __construct() {

        $this->objMemcache = new Memcache;
        if (_CACHE_MEMCACHE_CONSISTENT === true) {

            //修正memcache为一致性hash
            ini_set("memcache.hash_strategy", "consistent");
        }
    }


    /**
     * 初始化对像
     *
     * @param mixed $arrConfig
     */
    public function init($arrConfig) {

        if (is_array($arrConfig)) {

            foreach($arrConfig as $item) {

                $this->objMemcache->addServer($item['HOST'], $item['PORT']);
            }
        }
    }

    /**
     * 获取数据
     *
     * @param String $key 键值
     * @return Mixed
     */
    public function get($key) {

        if (_CACHE_SWITCH == true) {

            return $this->objMemcache->get($key);
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

            return $this->objMemcache->set($key, $value, MEMCACHE_COMPRESSED, $ttl);
        } else {

            return false;
        }


    }

    /**
     * 删除指定Key的内容
     *
     * @param String $key
     * @return bool|void
     */
    public function delete($key) {

        if (_CACHE_SWITCH == true) {

            return $this->objMemcache->delete($key);
        } else {

            return false;
        }

    }
}

?>
<?php
/**
 * Created by PhpStorm.
 * User: williamlu
 * Date: 14-8-8
 * Time: 上午13:32
 *
 * Redis缓存类
 * @version 1.0
 */
class Redis_cache extends abstract_cache {

    private $arrObjRedis = array();
    private $arrMasterConfig = null;
	private $arrSlaveConfig = null;


    /**
     * 析构
     */
    public function __construct() {

        // Do nothing;
	}


    /**
     * 初始化对像
     *
     * @param mixed $arrConfig
     */
    public function init($arrConfig) {

		if (is_array($arrConfig)) {

            $this->arrMasterConfig = current($arrConfig['MASTER']);
            $this->arrSlaveConfig = $arrConfig['SLAVE'][array_rand($arrConfig['SLAVE'], 1)];

		}
	}

    /**
     * 按照标志获取Redis
     *
     * @param   $strFlag 连接标识[M / S]
     * @return  mixed
     */
    private function & getRedisObj($strFlag) {

        if (empty($this->arrObjRedis)) {
            $this->arrObjRedis['M'] = new Redis;
            $this->arrObjRedis['M']->connect($this->arrMasterConfig['HOST'], $this->arrMasterConfig['PORT']);
            $this->arrObjRedis['M']->setOption(Redis::OPT_SERIALIZER, Redis::SERIALIZER_PHP);

            $this->arrObjRedis['S'] = new Redis;
            $this->arrObjRedis['S']->connect($this->arrSlaveConfig['HOST'], $this->arrSlaveConfig['PORT']);
            $this->arrObjRedis['S']->setOption(Redis::OPT_SERIALIZER, Redis::SERIALIZER_PHP);
        }

        return $this->arrObjRedis[$strFlag];
    }


    /**
     * 选择redis的DB区
     *
     * @param int $intDBNo DB区号
     */
    public function select($intDBNo = 0) {

        $this->getRedisObj('M')->select($intDBNo);
        $this->getRedisObj('S')->select($intDBNo);
	}


	/**
	 * 获取数据
	 * 
	 * @param String $key 键值
	 * @return Mixed
	 */
    public function get($key) {

        if (_CACHE_SWITCH == false) {

            return null;
        }

        return $this->getRedisObj('S')->get($key);
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

        if (_CACHE_SWITCH == false) {

            return false;
        }

        if ($this->getRedisObj('M')->set($key, $value)) {

            $this->getRedisObj('M')->expire($key, $ttl);
            return true;
        }

        return false;

	}

    /**
     * 如果不存在该键，设置缓存数据
     * @param $key
     * @param $value
     * @param int $ttl
     * @return bool|void
     */
    public function setnx($key, $value, $ttl = CACHE_DEFAULT_EXP_TIME) {

        if (_CACHE_SWITCH == false) {

            return false;
        }

        if($this->getRedisObj('M')->setnx($key, $value)){

            $this->getRedisObj('M')->expire($key, $ttl);
            return true;
        }

        return false;
    }


    /**
     * 验证键值是否存在
     * @param $key
     * @return mixed
     */
    public function exists($key) {

        if (_CACHE_SWITCH == false) {

            return false;
        }

        return $this->getRedisObj('S')->exists($key);
    }
	
	/**
	 * 删除指定Key的内容
	 * 
	 * @param $key
	 * @return void
	 */
	public function delete($key) {

        if (_CACHE_SWITCH == false) {

            return false;
        }

        return $this->getRedisObj('M')->delete($key);
	}

    /**
     * 自增数字值
     *
     * @param $key
     * @return mixed
     */
    public function incr($key) {

        if (_CACHE_SWITCH == false) {

            return false;
        }

        return $this->getRedisObj('M')->incr($key);
    }

    /**
     * 递减数字值
     *
     * @param $key
     * @return mixed
     */
    public function decr($key) {

        if (_CACHE_SWITCH == false) {

            return false;
        }

        return $this->getRedisObj('M')->decr($key);
    }

    /**
     * 取得所有指定键的值
     *
     * @param $arrkey
     * @return mixed
     */
    public function getMultiple($arrkey) {

        if (_CACHE_SWITCH == false) {

            return null;
        }

        return $this->getRedisObj('S')->getMultiple($arrkey);
    }

    /**
     * 其他调用直接传递给Redis对象执行,使用Master配置
     *
     * @param $function
     * @param $arrArguments
     * @return mixed
     */
    public function __call($function, $arrArguments) {

        if (_CACHE_SWITCH == false) {

            return false;
        }

        $intTmp = count($arrArguments);

        switch ($intTmp) {
            case 1:
                return $this->getRedisObj('M')->$function($arrArguments[0]);
            case 2:
                return $this->getRedisObj('M')->$function($arrArguments[0], $arrArguments[1]);
            case 3:
                return $this->getRedisObj('M')->$function($arrArguments[0], $arrArguments[1], $arrArguments[2]);
            default:
                return false;
        }
    }

}
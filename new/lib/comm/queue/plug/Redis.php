<?php
/**
 * Created by PhpStorm.
 * User: williamlu
 * Date: 14-8-12
 * Time: 下午7:30
 *
 * Redis队列类
 * @version 1.0
 */

class Redis_queue {

    private $objRedis = null;
    private $arrConfig = null;


    /**
     * 析构o
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

            $this->arrConfig = current($arrConfig['MASTER']);
        }
    }

    /**
     * 按照标志获取Redis
     *
     * @return  mixed
     */
    function & getRedisObj() {

        if ($this->objRedis == null) {

            $this->objRedis = new Redis();
            $this->objRedis->connect($this->arrConfig['HOST'], $this->arrConfig['PORT']);
            $this->objRedis->setOption(Redis::OPT_SERIALIZER, Redis::SERIALIZER_PHP);
       }

        return $this->objRedis;
    }

    /**
     * 选择redis的DB区
     *
     * @param int $intDBNo DB区号
     */
    public function select($intDBNo = 0) {

        $this->getRedisObj()->select($intDBNo);
    }

    /**
     * 队列尾部插入内容
     *
     * @param $queueName
     * @param $queueData
     * @return mixed
     */
    public function put($queueName, $queueData) {

        return $this->getRedisObj()->rpush($queueName, $queueData);
    }

    /**
     * 获取队列头部数据
     *
     * @param String $queueName 键值
     * @return Mixed
     */
    public function get($queueName) {

        return $this->getRedisObj()->lpop($queueName);
    }

    /**
     * 其他调用直接传递给Redis对象执行
     *
     * @param $function
     * @param $arrArguments
     * @return mixed
     */
    public function __call($function, $arrArguments) {

        $intTmp = count($arrArguments);

        switch ($intTmp) {
            case 1:
                return $this->getRedisObj()->$function($arrArguments[0]);
            case 2:
                return $this->getRedisObj()->$function($arrArguments[0], $arrArguments[1]);
            case 3:
                return $this->getRedisObj()->$function($arrArguments[0], $arrArguments[1], $arrArguments[2]);
            default:
                return false;

        }
    }


} 
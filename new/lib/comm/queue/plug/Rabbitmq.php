<?php
/**
 * Created by PhpStorm.
 * User: williamlu
 * Date: 14-8-22
 * Time: 下午11:50
 *
 * rabbitMQ 队列类
 * @version 1.0
 */

require_once('Rabbitmq_client.php');


class Rabbitmq_queue {

    private $arrConfig = null;
    private $arrRabbitMQ = array();

    /**
     * 析构
     */
    public function __construct() {

        // Do nothing;
    }

    /**
     * 初始化对像
     *
     * @param mixed $setting 配置参数
     * @return void
     */
    public function init($setting){

        $this->arrConfig = $setting;
    }

    /**
     * 获取队列对象
     *
     * @param $queueName 队列名
     * @return mixed
     */
    private function getObjRabbitMQ($queueName){

        if (!isset($this->arrRabbitMQ[$queueName])) {
            if (!isset($this->arrConfig[$queueName])) {
                if (__DEBUG) {
                    echo "RabbitMQ队列错误： <font color=red><i>".$queueName."</i> 链接配置不存在</font><br/>";
                }
                return false;
            }

            $objRabbitMQ = new rabbitmq_client($this->arrConfig[$queueName]['HOST'], $this->arrConfig[$queueName]['PORT'], $this->arrConfig[$queueName]['USR'], $this->arrConfig[$queueName]['PWD'], $this->arrConfig[$queueName]['VHOST'], $this->arrConfig[$queueName]['READTIMEOUT'], $this->arrConfig[$queueName]['WRITETIMEOUT'], $this->arrConfig[$queueName]['CONNECTTIMEOUT']);
            $this->arrRabbitMQ[$queueName] = $objRabbitMQ;
        }

        return $this->arrRabbitMQ[$queueName];
    }

    /**
     * 插入内容
     *
     * @param $queueName 队列名
     * @param $queueData 要保存的内容
     * @return bool|void
     */
    public function put($queueName, $queueData){

        $objRabbitMQ =  $this->getObjRabbitMQ($queueName);
        if (!$objRabbitMQ) {
            return false;
        }

        return $objRabbitMQ->put($queueName, $queueData);
    }

    /**
     * 取出内容
     *
     * @param $queueName 队列名
     * @return bool|void
     */
    public function get($queueName){

        $objRabbitMQ =  $this->getObjRabbitMQ($queueName);
        if (!$objRabbitMQ) {
            return false;
        }

        return $objRabbitMQ->get($queueName);
    }

    /**
     * 获取状态信息
     *
     * @param $queueName
     * @return bool
     */
    public function status($queueName){

        $objRabbitMQ =  $this->getObjRabbitMQ($queueName);
        if (!$objRabbitMQ) {
            return false;
        }

        return $objRabbitMQ->status($queueName);
    }

    /**
     * 删除队列
     *
     * @param $queueName
     * @return bool
     */
    public function delete($queueName){

        $objRabbitMQ =  $this->getObjRabbitMQ($queueName);
        if (!$objRabbitMQ) {
            return false;
        }

        return $objRabbitMQ->delete($queueName);
    }

    /**
     * 重置队列
     *
     * @param $queueName
     * @return bool
     */
    public function reset($queueName){

        $objRabbitMQ =  $this->getObjRabbitMQ($queueName);
        if (!$objRabbitMQ) {
            return false;
        }

        return $objRabbitMQ->reset($queueName);
    }
}


<?php
/**
 * Created by PhpStorm.
 * User: williamlu
 * Date: 14-8-22
 * Time: 下午11:58
 *
 * rabbitMQ_Client类
 * @version 1.0
 */


class Rabbitmq_client {

    private $_host;
    private $_port;
    private $_userName;
    private $_userPwd;
    private $_vhost;
    private $_readTimeout = 1;
    private $_writeTimeout = 1;
    private $_connectTimeout = 1;
    private $_blAuth = true;

    /**
     * 析构
     *
     * @param $host
     * @param $port
     * @param string $userName
     * @param string $userPwd
     * @param string $vhost
     * @param int $readTimeout
     * @param int $writeTimeout
     * @param int $connectTimeout
     */
    public function __construct($host, $port, $userName = '', $userPwd = '', $vhost = '', $readTimeout = 1, $writeTimeout = 1, $connectTimeout = 1) {

        $this->_host = $host;
        $this->_port = $port;
        $this->_userName = $userName;
        $this->_userPwd = $userPwd;
        $this->_vhost = $vhost;
        $this->_readTimeout = $readTimeout;
        $this->_writeTimeout = $writeTimeout;
        $this->_connectTimeout = $connectTimeout;

        if (is_empty($this->_userName)) {

            $this->_blAuth = false;

        }
        return true;
    }


    /**
     * 插入消息
     *
     * @param $queue_name
     * @param $queue_data
     * @return bool
     */
    public function put($queue_name, $queue_data){

//        echo 'RabbitMQ put: '.$this->_host.'---'.$this->_port.'---'.$queue_name.'---'.$queue_data;
//        echo '<br/>';
//        return true;

        // 队列名称为空或者队列内容为空直接退出return false
        if (empty($queue_name) || empty($queue_data)) {
            return false;
        }

        if ($this->_blAuth) {

            $param = array('host'=>$this->_host, 'port'=>$this->_port, 'login'=>$this->_userName,'password'=>$this->_userPwd, 'vhost'=>$this->_vhost, 'read_timeout'=>$this->_readTimeout, 'write_timeout'=>$this->_writeTimeout, 'connect_timeout'=>$this->_connectTimeout);
        } else {

            $param = array('host'=>$this->_host, 'port'=>$this->_port, 'read_timeout'=>$this->_readTimeout, 'write_timeout'=>$this->_writeTimeout, 'connect_timeout'=>$this->_connectTimeout);
        }

        $conn = new AMQPConnection($param) or die('init fail');
        $conn->connect() or die('conn fail');
        $exchange_name = strtolower(trim($queue_name."_exchange"));
        $queue_name = strtolower(trim($queue_name));

        $channel = new AMQPChannel($conn);

        $exchange = new AMQPExchange($channel);
        $exchange->setName($exchange_name);
        $exchange->setType(AMQP_EX_TYPE_DIRECT);
        $exchange->setFlags(AMQP_DURABLE);
        $exchange->declare();

        $queue = new AMQPQueue($channel);
        $queue->setName($queue_name);
        $queue->setFlags(AMQP_DURABLE);
        $queue->declare();

        $queue->bind($exchange_name, $queue_name);
        $channel->startTransaction();
        $exchange->publish($queue_data, $queue_name,AMQP_NOPARAM, array('delivery_mode'=>2));
        $channel->commitTransaction();
        $conn->disconnect();
        return true;
    }

    /**
     * 获取消息
     *
     * @param $queue_name
     * @return bool
     */
    public function get($queue_name){

//        echo 'RabbitMQ get: '.$this->_host.'---'.$this->_port.'---'.$queue_name;
//       // echo '<br/>';
//        return true;

        if ($this->_blAuth) {

            $param = array('host'=>$this->_host, 'port'=>$this->_port, 'login'=>$this->_userName,'password'=>$this->_userPwd, 'vhost'=>$this->_vhost, 'read_timeout'=>$this->_readTimeout, 'write_timeout'=>$this->_writeTimeout, 'connect_timeout'=>$this->_connectTimeout);
        } else {

            $param = array('host'=>$this->_host, 'port'=>$this->_port, 'read_timeout'=>$this->_readTimeout, 'write_timeout'=>$this->_writeTimeout, 'connect_timeout'=>$this->_connectTimeout);
        }

        $conn = new AMQPConnection($param) or die('init fail');
        $conn->connect() or die('conn fail');
        $exchange_name = strtolower(trim($queue_name."_exchange"));
        $queue_name = strtolower(trim($queue_name));

        $channel = new AMQPChannel($conn);

        $exchange = new AMQPExchange($channel);
        $exchange->setName($exchange_name);
        $exchange->setType(AMQP_EX_TYPE_DIRECT);
        $exchange->setFlags(AMQP_DURABLE);
        $exchange->declare();

        $queue = new AMQPQueue($channel);
        $queue->setName($queue_name);
        $queue->setFlags(AMQP_DURABLE);
        $queue->declare();

        $queue->bind($exchange_name, $queue_name);

        $message = $queue->get(AMQP_AUTOACK);
        $res = false;
        if($message && !empty($message)){
            $res = $message->getBody();
        }
        $conn->disconnect();
        return $res;
    }


    /**
     * 获取队列状态信息
     *
     * @param $queue_name
     * @return mixed
     */
    public function status($queue_name){
        $param = array('host'=>$this->httpsqs_host, 'port'=>$this->httpsqs_port, 'login'=>$this->userName,'password'=>$this->userPwd, 'vhost'=>$this->vhost, 'read_timeout'=>$this->readTimeout, 'write_timeout'=>$this->writeTimeout, 'connect_timeout'=>$this->connTimeout);
        $conn = new AMQPConnection($param) or die('init fail');
        $conn->connect() or die('conn fail');
        $exchange_name = strtolower(trim($queue_name."_exchenge"));
        $queue_name = strtolower(trim($queue_name));

        $channel = new AMQPChannel($conn);

        $exchange = new AMQPExchange($channel);
        $exchange->setName($exchange_name);
        $exchange->setType(AMQP_EX_TYPE_DIRECT);
        $exchange->setFlags(AMQP_DURABLE);
        $exchange->declare();

        $queue = new AMQPQueue($channel);
        $queue->setName($queue_name);
        $queue->setFlags(AMQP_PASSIVE);
        $messageCount = $queue->declare();
        $conn->disconnect();
        return $messageCount;
    }


    /**
     * 删除队列
     *
     * @param $queue_name
     * @return mixed
     */
    public function delete($queue_name){
        $param = array('host'=>$this->httpsqs_host, 'port'=>$this->httpsqs_port, 'login'=>$this->userName,'password'=>$this->userPwd, 'vhost'=>$this->vhost, 'read_timeout'=>$this->readTimeout, 'write_timeout'=>$this->writeTimeout, 'connect_timeout'=>$this->connTimeout);
        $conn = new AMQPConnection($param) or die('init fail');
        $conn->connect() or die('conn fail');
        $exchange_name = strtolower(trim($queue_name."_exchenge"));
        $queue_name = strtolower(trim($queue_name));

        $channel = new AMQPChannel($conn);

        $exchange = new AMQPExchange($channel);
        $exchange->setName($exchange_name);
        $exchange->setType(AMQP_EX_TYPE_DIRECT);
        $exchange->setFlags(AMQP_DURABLE);
        $exchange->declare();

        $queue = new AMQPQueue($channel);
        $queue->setName($queue_name);
        $queue->setFlags(AMQP_DURABLE);
        $res = $queue->delete();
        $conn->disconnect();
        return $res;
    }


    /**
     * 重置队列
     *
     * @param $queue_name
     * @return mixed
     */
    public function reset($queue_name){
        $param = array('host'=>$this->httpsqs_host, 'port'=>$this->httpsqs_port, 'login'=>$this->userName,'password'=>$this->userPwd, 'vhost'=>$this->vhost, 'read_timeout'=>$this->readTimeout, 'write_timeout'=>$this->writeTimeout, 'connect_timeout'=>$this->connTimeout);
        $conn = new AMQPConnection($param) or die('init fail');
        $conn->connect() or die('conn fail');
        $exchange_name = strtolower(trim($queue_name."_exchenge"));
        $queue_name = strtolower(trim($queue_name));

        $channel = new AMQPChannel($conn);

        $exchange = new AMQPExchange($channel);
        $exchange->setName($exchange_name);
        $exchange->setType(AMQP_EX_TYPE_DIRECT);
        $exchange->setFlags(AMQP_DURABLE);
        $exchange->declare();

        $queue = new AMQPQueue($channel);
        $queue->setName($queue_name);
        $queue->setFlags(AMQP_DURABLE);
        $res = $queue->purge();
        $conn->disconnect();
        return $res;
    }
}

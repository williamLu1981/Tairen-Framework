<?php
/**
 * Created by PhpStorm.
 * User: williamlu
 * Date: 14-8-11
 * Time: 下午4:55
 *
 * httpsqs类
 * @version 1.0
 */

//包含第三方httpsqs_client类
require_once('Httpsqs_client.php');


class Httpsqs_queue extends Httpsqs_client {

    private $arrConfig = null;
    private $arrHttpSqs = array();

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
    private function getObjHttpSqs($queueName){

        if (!isset($this->arrHttpSqs[$queueName])) {
            if (!isset($this->arrConfig[$queueName])) {
                if (__DEBUG) {
                    echo "HTTPSQS队列错误： <font color=red><i>".$queueName."</i> 链接配置不存在</font><br/>";
                }
                return false;
            }

            $objHttpSqs = new httpsqs_client($this->arrConfig[$queueName]['HOST'], $this->arrConfig[$queueName]['PORT'], $this->arrConfig[$queueName]['AUTH'], $this->arrConfig[$queueName]['CHARSET']);
            $this->arrHttpSqs[$queueName] = $objHttpSqs;
        }

        return $this->arrHttpSqs[$queueName];
    }

    /**
     * 插入内容
     *
     * @param $queueName 队列名
     * @param $queueData 要保存的内容
     * @return bool|void
     */
    public function put($queueName, $queueData){

        $objHttpSqs =  $this->getObjHttpSqs($queueName);
        if (!$objHttpSqs) {
            return false;
        }

        return $objHttpSqs->put($queueName, $queueData);
    }

    /**
     * 取出内容
     *
     * @param $queueName 队列名
     * @return bool|void
     */
    public function get($queueName){

        $objHttpSqs =  $this->getObjHttpSqs($queueName);
        if (!$objHttpSqs) {
            return false;
        }

        return $objHttpSqs->get($queueName);
    }

    /**
     * 取出内容和位置索引号
     *
     * @param $queueName
     * @return bool
     */
    public function gets($queueName){

        $objHttpSqs =  $this->getObjHttpSqs($queueName);
        if (!$objHttpSqs) {
            return false;
        }

        return $objHttpSqs->gets($queueName);
    }

    /**
     * 获得队列状态信息
     *
     * @param $queueName
     * @return bool
     */
    public function status($queueName){

        $objHttpSqs =  $this->getObjHttpSqs($queueName);
        if (!$objHttpSqs) {
            return false;
        }

        return $objHttpSqs->status($queueName);
    }

    /**
     * 获得队列状态信息Json格式
     *
     * @param $queueName
     * @return bool
     */
    public function statusJson($queueName){

        $objHttpSqs =  $this->getObjHttpSqs($queueName);
        if (!$objHttpSqs) {
            return false;
        }

        return $objHttpSqs->status_json($queueName);
    }

    /**
     * 返回指定位置的数据内容，并不弹出内容
     *
     * @param $queueName
     * @param $queuePosition
     * @return bool
     */
    public function view($queueName, $queuePosition){

        $objHttpSqs =  $this->getObjHttpSqs($queueName);
        if (!$objHttpSqs) {
            return false;
        }

        return $objHttpSqs->view($queueName, $queuePosition);
    }

    /**
     * 重置队列
     *
     * @param $queueName
     * @return bool
     */
    public function reset($queueName){

        $objHttpSqs =  $this->getObjHttpSqs($queueName);
        if (!$objHttpSqs) {
            return false;
        }

        return $objHttpSqs->reset($queueName);
    }

    /**
     * 设置队列的最大存储上限
     *
     * @param $queueName
     * @param $number
     * @return bool
     */
    public function maxQueue($queueName, $number){

        $objHttpSqs =  $this->getObjHttpSqs($queueName);
        if (!$objHttpSqs) {
            return false;
        }

        return $objHttpSqs->maxqueue($queueName, $number);
    }

}
?>

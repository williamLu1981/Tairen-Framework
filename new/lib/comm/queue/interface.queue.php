<?php
/**
 * Created by PhpStorm.
 * User: williamlu
 * Date: 14-8-8
 * Time: 下午6:18
 *
 * queue类的接口文件
 * @version 1.0
 */
interface interface_queue {

    /**
     * 初始化对像
     *
     * @param mixed $setting 配置参数
     * @return void
     */
    public function init($setting);
    /**
     * 插入内容
     *
     * @param String $name 队列名
     * @param Mixed $value 要保存的内容
     *
     * @return void
     */
    public function put($name, $value);

    /**
     * 取出内容
     *
     * @param $name 队列名
     * @return Mixed
     */
    public function get($name);

}

?>
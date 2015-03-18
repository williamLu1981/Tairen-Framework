<?php
/**
 * Created by PhpStorm.
 * User: williamlu
 * Date: 14-8-4
 * Time: 下午4:44
 *
 * Cache 类的接口文件
 * @version 1.0
 */
interface interface_cache {

    /**
     * 初始化对像
     *
     * @param mixed $setting 配置参数
     * @return void
     */
    public function init($setting);
    /**
     * 保存缓存内容
     *
     * @param String $key 键值
     * @param Mixed $value 要保存的内容
     * @param integer $ttl 缓存时间
     *
     * @return void
     */
    public function set($key, $value, $ttl);

    /**
     * 获取指定内容
     *
     * @param $key 键值
     * @return Mixed
     */
    public function get($key);

    /**
     * 删除指定键值内容
     *
     * @param String $key 键值
     * @return void
     */
    public function delete($key);
}

?>
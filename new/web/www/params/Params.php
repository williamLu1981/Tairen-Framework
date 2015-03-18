<?php
/**
 * Created by PhpStorm.
 * User: williamlu
 * Date: 14-8-27
 * Time: 下午5:21
 *
 * 控制器注册
 * @version 1.0
 */

class Params extends urlparse {

    function params() {
        //模块注册
        array_push($this->actionList,'hello');	//init hello world~

    }

    function runFirst() {

    }
}

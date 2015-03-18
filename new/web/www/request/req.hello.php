<?php
/**
 * Created by PhpStorm.
 * User: williamlu
 * Date: 14-8-27
 * Time: 下午5:21
 *
 * request注册
 * @version 1.0
 */
class req_hello extends request {

	function req_hello() {

		array_push($this->doAttr,'addUser1');
        array_push($this->doAttr,'addUser2');
        array_push($this->doAttr,'addUser3');
        array_push($this->doAttr,'addUser4');
	}
}

?>
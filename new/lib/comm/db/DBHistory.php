<?php
/**
 * Created by PhpStorm.
 * User: williamlu
 * Date: 14-8-4
 * Time: 下午14:26
 *
 * 数据库错误信息类
 * @version 	1.0
 */


//以后考虑信息展示过滤时使用
define('__DB_SQL_ERROE', 1);
define('__DB_SQL_INFO',	2);

class DBHistory {

    //记录信息的最多条数
    private $_recordMax = 256;

    //信息列表
    private $_content = array();


    /**
     * 增加消息记录
     *
     * @param $host 主机名
     * @param $db   当前数据库
     * @param $sql  查询SQL
     * @param $blAutoCommit AutoCommit状态值
     * @param $info 说明信息
     * @param $intFlag 标记异常还是正常
     */
    function add($host, $db, $sql, $blAutoCommit, $info, $intFlag){

        if(count($this->_content) < $this->_recordMax){
            $this->_content[] = array('HOST' => $host, 'DB' => $db, 'SQL' => $sql, 'AUTOCOMMIT' => $blAutoCommit, 'INFO' => $info, 'FLAG' => $intFlag);
        }
    }


    /**
     * 组合SQL信息
     *
     * @param $arrInfo 查询信息
     * @param $key
     * @return string
     */
    function _fetchStrFromInfo($arrInfo, $key) {

        $_ret = sprintf("<tr bgcolor=\"E2E2E2\"><td>%02d</td><td align=left>%s => %s</td><td align=left> %s</td> <td align=left> %s </td><td align=left> %s </td></tr>",$key+1, $arrInfo['HOST'], $arrInfo['DB'], $arrInfo['SQL'], $arrInfo['AUTOCOMMIT'], $arrInfo['INFO']);
        return $_ret;
    }


    /**
     * 显示所有信息
     */
    function display()  {

        echo "<style> body{font-size:9pt;}	td{font-size:9pt;} </style>";
        echo "<table border=\"0\" cellpadding=\"4\" cellspacing=\"1\" width=\"100%\">";
        echo "<tr bgcolor=\"#D2D2D2\"><td width=5%>序号</td><td width=15%>主机 => 数据库名</td><td width=40%>SQL命令</td> <td width=10%>AutoCommit状态值</td><td width=30%>执行结果</td></tr>";
        foreach($this->_content as $key => $info){
            echo $this->_fetchStrFromInfo($info, $key);
        }
        echo "</table>";
    }

    /**
     * 析构时判断，如果DB调试打开则打印信息
     */
    function __destruct() {

        if (defined('__DB_DEBUG') && __DB_DEBUG === true) {

            $this->display();
        }
    }
}
?>
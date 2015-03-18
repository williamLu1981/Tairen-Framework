<?php
/**
 * Created by PhpStorm.
 * User: williamlu
 * Date: 14-8-4
 * Time: 下午18:45
 *
 * Mysql数据库代理
 * @version 1.0
 */

require_once(dirname(__FILE__) . '/Mysql.php');


class DBProxy extends Mysql{

    //连接标识
    private $_flag = null;

    //最后执行操作的标志
    private $_lastExecFlag = null;

    //主从数据库链接
    private $_dbObj = array();

    /**
     * 析构
     */
    function __construct($flag) {

        $this->_flag = $flag;
    }

    /**
     * 取得对应数据库的连接参数
     *
     * @access  public
     * @param	$flag  数据库标记
     * @return  mixed
     */
    function & getDSN($flag) {

        $flag = strtoupper($flag);

        if (isset($GLOBALS[$flag])) {

            return $GLOBALS[$flag];
        } else {

            return null;
        }
    }

    /**
     * 按照标志获取对应数据库链接
     *
     * @param   $flag
     * @return  mixed
     */
    function & getDbObj($flag) {
        if (empty($this->_dbObj)) {
            $this->_dbObj['M'] = new Mysql;
            $this->_dbObj['S'] = new Mysql;
            $this->_dbObj['M']->connect($this->getDSN($this->_flag . '_MASTER'));
//            $this->_dbObj['M']->_FLAG = 'M';
            $this->_dbObj['S']->connect($this->getDSN($this->_flag . '_SLAVE'));
//            $this->_dbObj['S']->_FLAG = 'S';
        }



        return $this->_dbObj[$flag];
    }

    /**
     * 取得SQL后数据库受影响的记录条数
     *
     * @param 	void
     * @return	mixed
     */
    function affectedRows(){

        return $this->getDbObj($this->_lastExecFlag)->affectedRows();
    }

    /**
     * 关闭当前数据库连接
     *
     * @param 	void
     * @return 	void
     */
    function close(){

        $this->getDbObj('M')->close();
        $this->getDbObj('S')->close();
    }

    /**
     * 连接到指定数据库
     *
     * @param 	array $dsn
     * @return	boolean
     */
    function connect(&$dsn){

        //
    }

    /**
     * 取得执行插入命令后的自增加字段的值
     *
     * @param 	void
     * @return 	mixed
     */
    function insertId() {

        return $this->getDbObj($this->_lastExecFlag)->insertId();
    }

    /**
     * 执行SQL命令
     *
     * @param 	string	$sql	SQL命令
     * @param 	string	$queryType	返回数据类型
     * @return 	mixed
     */
    /**
     * 执行SQL命令
     *
     * @param SQL命令 $sql
     * @param null $queryType   返回数据类型
     * @return mixed|resource
     */
    function & query($sql, $queryType = null){

        if (preg_match('/^select/is',trim($sql))) {

            $this->_lastExecFlag = 'S';
        } else {
            $this->_lastExecFlag = 'M';
        }

        return $this->getDbObj($this->_lastExecFlag)->query($sql, $queryType);
    }

    /**
     * 选择当前操作数据库
     *
     * @param 	string $db
     * @return 	false;
     */
    function selectDB($db = null) {

        $this->getDbObj('M')->selectDB($db);
        $this->getDbObj('S')->selectDB($db);
    }

}
?>
<?php
/**
 * Created by PhpStorm.
 * User: williamlu
 * Date: 14-8-4
 * Time: 下午15:44
 *
 * DB源管理类
 */


//写模式
define('__MASTER', FALSE);
//读模式
define('__SLAVE', TRUE);

//定义配置文件
if (! defined('CONFIG_DB_INCLUDED')) {

    require_once("config/config.db.php");
}

class DBSource {

    //所有可用的数据库连接对像
    var $_arrConnects = array ();

    /**
     * 创建新的数据库联接
     *
     * @access  private
     * @param	$strFlag  数据库标识
     * @return	mixed
     */
    function _createConnect($strFlag) {

        $strFlag = strtoupper($strFlag);

        $arrDSNInfo = $this->getDSN($strFlag);

        if ($arrDSNInfo) {

            $this->_arrConnects[$strFlag] = new Mysql();
            $this->_arrConnects[$strFlag]->connect($arrDSNInfo);
            return $this->_arrConnects[$strFlag];
        } else {

            return null;
        }
    }

    /**
     * 创建一个数据库链接代理
     *
     * @access  private
     * @param	$strFlag  数据库标识
     * @return	mixed
     */
    function _createProxy($strFlag) {

        $strFlag = strtoupper($strFlag);

        if ($strFlag) {
            if (! class_exists('DBproxy')) {
                require_once (dirname(__FILE__) . "/DBProxy.php");
            }
            $this->_arrConnects[$strFlag] = new DBProxy($strFlag);

            return $this->_arrConnects[$strFlag];
        } else {
            return null;
        }
    }

    /**
     * 取得对应数据库的连接参数
     *
     * @access  public
     * @param	$strFlag  数据库标记
     * @return  mixed
     */
    function getDSN($strFlag) {

        $strFlag = strtoupper($strFlag);

        if (isset($GLOBALS[$strFlag])) {
            return $GLOBALS[$strFlag];
        } else {
            return null;
        }
    }

    /**
     * 取得指定数据库的联接
     *
     * @param $strFlag 数据库标记
     * @param null $blRead
     * @return mixed
     */
    function getConnect($strFlag, $blRead = null) {

        $strFlag = strtoupper($strFlag);

        if ($blRead !== null) {
            if ($blRead) {
                $strFlag = sprintf("%s_%s", $strFlag, 'SLAVE');
            } else {
                $strFlag = sprintf("%s_%s", $strFlag, 'MASTER');
            }

            if (isset($this->_arrConnects[$strFlag])) {
                return $this->_arrConnects[$strFlag];
            } else {
                return $this->_createConnect($strFlag);
            }
        } else {
            if (isset($this->_arrConnects[$strFlag])) {
                return $this->_arrConnects[$strFlag];
            } else {
                return $this->_createProxy($strFlag);
            }
        }
    }

    /**
     * 输出调试信息
     */
    function toString() {

        foreach ( $this->_arrConnects as $k => $v ) {
            $k = $k . sprintf("_%s_%s", $v->host, $v->user);
            $arrTmp[$k] = $v->queries;
        }

        print_r($arrTmp);
    }
}


//if (!class_exists('Mysql')) {
//    //如果不存在Mysql类,则自动包含同目录下的Mysql.php文件
//    require_once(dirname(__FILE__) . '/Mysql.php');
//}

?>
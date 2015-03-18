<?php
/**
 * Created by PhpStorm.
 * User: williamlu
 * Date: 14-8-4
 * Time: 下午16:40
 *
 * Mysql数据库基础类
 * @version 1.0
 */

class Mysql {

    /**
     * 数据库联接字
     *
     * @var res
     */
    private $_linkId = null;

    /**
     * 数据库主机
     *
     * @var	string
     */
    private $_hostName = null;

    /**
     * 数据库主机端口
     *
     * @var	string
     */
    private $_port = null;

    /**
     * 数据库名称
     *
     * @var	integer
     */
    private $_dataBase = null;

    /**
     * 数据库用户名
     *
     * @var	string
     */
    private $_userName = null;

    /**
     * 数据库密码
     *
     * @var	string
     */
    private $_passWord = null;

    // 标记自动提交状态值
    private $_blAutoCommit = true;

    // bind_param 信息存储
    private $_objMysqliStmt = null;
    private $_arrBindVal = array();
    private $_strBindTypes = '';


    /**
     * 设置数据库信息
     *
     * @param 	array $dsn
     * @return 	void
     */
    public function setDsn($dsn) {
        $this->_hostName = $dsn['HOST'];
        $this->_port = $dsn['PORT'];
        $this->_dataBase = $dsn['NAME'];
        $this->_userName = $dsn['USER'];
        $this->_passWord = $dsn['PASS'];
    }

    /**
     * 连接到指定数据库
     *
     * @param 	array $dsn
     * @return	boolean
     */
    public function connect(&$dsn) {
        $this->setDsn($dsn);
    }


    /**
     * 选择当前操作数据库
     *
     * @param 	string $db
     * @return 	false;
     */
    public function selectDB($db = null) {

        $db = empty($db) ? $this->_dataBase : $db;
        if ($db) {
            if (is_object($this->_linkId)) {

                $blResult = @mysqli_select_db($this->_linkId, $db);

                if (!$blResult) {
                    $this->_throwErr("USE {$this->_dataBase};");
                } else {
                    $this->_addDbHistory("USE {$this->_dataBase};");
                }
            }

            $this->_dataBase = $db;
        }
    }


    /**
     * 取得数据库链接
     *
     * @param	void
     * @return 	res
     */
    public function _getLinkId() {

        if (! is_object($this->_linkId)) {

            $this->_linkId = @mysqli_connect($this->_hostName, $this->_userName, $this->_passWord, $this->_dataBase, $this->_port);

            if (!$this->_linkId) {

                $this->_throwErr("mysqli_connect({$this->_hostName}, {$this->_userName}, {$this->_passWord}, {$this->_dataBase}, {$this->_port});");
            } else {

                if (defined('__DB_DEBUG') && __DB_DEBUG) {

                    $this->_addDbHistory("mysqli_connect({$this->_hostName}, {$this->_userName}, {$this->_passWord}, {$this->_dataBase}, {$this->_port});");
                }
            }

            if ($this->_version() > '4.1') {

                @mysqli_query($this->_linkId, "SET character_set_connection=UTF8, character_set_results=UTF8, character_set_client=binary");

                if ($this->_version() > '5.0.1') {

                    @mysqli_query($this->_linkId, "SET sql_mode=''");
                }
            }

            $blResult = @mysqli_select_db($this->_linkId, $this->_dataBase);
            if (!$blResult) {

                $this->_throwErr("USE {$this->_dataBase};");
            } else {

                $this->_addDbHistory("USE {$this->_dataBase};");
            }
        }

        return $this->_linkId;
    }

    /**
     * 获取版本号
     */
    public function _version() {

        return @mysqli_get_server_info($this->_linkId);
    }



    /**
     * 真实执行SQL
     *
     * @param $sql
     * @return resource
     */
    public function &_realQuery($sql) {

        $linkId = $this->_getLinkId();

        if (defined('__DB_DEBUG') && __DB_DEBUG) {
            echo "linkId:";
            var_dump(mysqli_thread_id($linkId));
        }

        $result = @mysqli_query($linkId, $sql);
        if (! $result) {

            $this->_throwErr($sql);
        } else {

            if (defined('__DB_DEBUG') && __DB_DEBUG) {

                $this->_addDbHistory($sql);
            }
        }

        return $result;
    }



    /**
     * 执行SQL命令
     *
     * @param $sql  SQL命令
     * @param null $queryType   返回数据类型
     * @return mixed|resource
     */
    public function &query($sql, $queryType = null) {
        $sql = trim($sql);

        //实时查询
        $result = $this->_realQuery($sql);

        if (empty($queryType)) {
            return $result;
        } else {
            return $this->_getValue($result, $queryType);
        }
    }

    /**
     * 按查询类型返回对应的值
     *
     * @param 	mixed	$result	查询结果
     * @param 	string	$queryType	返回类型(1; row)
     * @return 	mixed
     */
    public function &_getValue($result, $queryType) {

        $queryType = strtolower($queryType);

        switch ($queryType) {
            case '1' :
                $row = $this->fetchRow($result);
                $_ret = $row[0];
                break;
            case 'row' :
                $_ret = $this->fetchRow($result);
                break;
            default :
                $_ret = $this->fetchArray($result);
                break;
        }

        return $_ret;
    }

    /**
     * 从结果集中返回一条数据
     *
     * @param mixed	$res
     * @return array
     */
    public function &fetchArray(& $res) {

        if (is_object($res)) {

            $arrResult = @mysqli_fetch_array($res, MYSQL_ASSOC);
            return $arrResult;
        }  else {

            if (defined('__DB_DEBUG') && __DB_DEBUG) {

                $this->_addDbHistory('无法识别的数据库结果集 $res => ' . gettype($res));
            }

            return null;
        }
    }

    /**
     * 重结果集中返回一条数据,字段名为下标
     *
     * @param mixed	$res
     * @return array
     */
    public function fetchRow(&$res) {

        if (is_object($res)) {

            return @mysqli_fetch_row($res);
        } else {

            if (defined('__DB_DEBUG') && __DB_DEBUG) {

                $this->_addDbHistory('无法识别的数据库结果集 $res => ' . gettype($res));
            }

            return null;
        }
    }

    /**
     * 取得返回数据集中的数据条数
     *
     * @param mixed	$res
     * @return integer
     */
    public function getRowNum(& $res) {

        if (is_object($res)) {

            return @mysqli_num_rows($res);
        } else {

            if (defined('__DB_DEBUG') && __DB_DEBUG) {

                $this->_addDbHistory('无法识别的数据库结果集 $res => ' . gettype($res));
            }

            return null;
        }
    }

    /**
     * 取得返回数据集中一个记录里的第一个字段的值
     *
     */
    public function result($res, $pos) {

        if (is_object($res)) {

            $this->dataSeek($res, $pos);
            $arrTmp = @mysqli_fetch_array($res, MYSQLI_NUM);
            $this->dataSeek($res, 0);
            return $arrTmp[0];

        } else {

            if (defined('__DB_DEBUG') && __DB_DEBUG) {

                $this->_addDbHistory('无法识别的数据库结果集 $res => ' . gettype($res));
            }

            return null;
        }
    }

    /**
     * 移动当前数据指针
     *
     * @param 	mixed	$res 	数据结果集
     * @param 	integer $pos	指针位置
     * @return 	mixed
     */
    public function dataSeek($res, $pos = 0) {

        if (is_object($res)) {

            return @mysqli_data_seek($res, $pos);
        } else {

            if (defined('__DB_DEBUG') && __DB_DEBUG) {

                $this->_addDbHistory('无法识别的数据库结果集 $res => ' . gettype($res));
            }

            return NULL;
        }
    }

    /**
     * 取得SQL后数据库受影响的记录条数
     *
     * @param 	void
     * @return	mixed
     */
    public function affectedRows() {

        if (is_object($this->_linkId)) {

            return @mysqli_affected_rows($this->_linkId);
        } else {

            return null;
        }
    }

    /**
     * 取得执行插入命令后的自增加字段的值
     *
     * @param 	void
     * @return 	mixed
     */
    public function insertId() {

        if (is_object($this->_linkId)) {

            return @mysqli_insert_id($this->_linkId);
        }
    }

    /**
     * 关闭当前数据库连接
     *
     * @param 	void
     * @return 	void
     */
    public function close() {

        if (is_object($this->_linkId)) {

            @mysqli_close($this->_linkId);
        }
    }

    /**
     * 删除结果集
     *
     * @param resource	$res
     * @return void
     */
    public function freeResult($res) {

        if (is_object($res)) {

            return @mysqli_free_result($res);
        }
    }

    /**
     * 取得数据库查询信息类
     *
     * @param 	void
     * @return 	object
     */
    public function &_getHistoryObj() {

        if (! isset($GLOBALS['db_history_obj'])) {

            require_once (dirname(__FILE__) . '/DBHistory.php');
            $GLOBALS['db_history_obj'] = new dbHistory();
        }

        return $GLOBALS['db_history_obj'];
    }


    /**
     * 增加错误信息
     *
     * @param string $sql
     */
    public function _throwErr($sql = '') {

        $_errInfo = '';

        $_obj = & $this->_getHistoryObj();

        if ($this->_linkId) {

            $_errInfo = @mysqli_error($this->_linkId);
        }

        $_errInfo = $_errInfo ? $_errInfo : '执行错误';

        $_obj->add($this->_hostName, $this->_dataBase, $sql, $this->_blAutoCommit ? 'TRUE' : 'FALSE', "<font color=red>".$_errInfo."</font>", __DB_SQL_ERROE);
    }


    /**
     * 增加调试信息
     *
     * @param $sql
     * @param null $info
     */
    public function _addDbHistory($sql, $info = null) {

        $_obj = & $this->_getHistoryObj();
        $info = $info ? $info : '执行正确';

        $_obj->add($this->_hostName, $this->_dataBase, $sql, $this->_blAutoCommit ? 'TRUE' : 'FALSE', $info, __DB_SQL_INFO);
    }

    /**
     * 初始化事务操作准备
     *
     */
    public function startTransaction() {

        $linkId = $this->_getLinkId();

        /* 设置关闭 autocommit */
        if (mysqli_autocommit($linkId, false)) {

            $this->_blAutoCommit = false;

            if (defined('__DB_DEBUG') && __DB_DEBUG) {

                $this->_addDbHistory('事务操作准备 mysqli_autocommit false');
            }
            return true;
        } else {

            if (defined('__DB_DEBUG') && __DB_DEBUG) {

                $this->_throwErr('事务操作准备 mysqli_autocommit false');
            }


            return false;
        }


    }

    /**
     * 事务操作结束工作
     *
     */
    public function endTransaction() {

        $linkId = $this->_getLinkId();
        /* 设置开启 autocommit */
        if (mysqli_autocommit($linkId, true)) {

            $this->_blAutoCommit = true;
            if (defined('__DB_DEBUG') && __DB_DEBUG) {

                $this->_addDbHistory('事务操作结束 mysqli_autocommit true');
            }
            return true;
        } else {

            if (defined('__DB_DEBUG') && __DB_DEBUG) {

                $this->_throwErr('事务操作结束 mysqli_autocommit true');
            }
            return false;
        }
    }


    /**
     * 事务回退操作
     *
     * @param bool $blEndTran
     * @return bool
     */
    public function rollback($blEndTran = true) {

        $linkId = $this->_getLinkId();

        if (mysqli_rollback($linkId)) {

            if (defined('__DB_DEBUG') && __DB_DEBUG) {

                $this->_addDbHistory('事务操作回退 rollback');
            }

            if ($blEndTran == true) {

                $this->endTransaction();
            }


            return true;
        } else {

            if (defined('__DB_DEBUG') && __DB_DEBUG) {

                $this->_throwErr('事务操作回退 rollback');
            }
            return false;
        }

    }


    /**
     * 事务提交操作
     *
     * @param bool $blEndTran
     * @return bool
     */
    public function commit($blEndTran = true) {

        $linkId = $this->_getLinkId();
        if (mysqli_commit($linkId)) {

            if (defined('__DB_DEBUG') && __DB_DEBUG) {

                $this->_addDbHistory('事务操作提交 commit');
            }

            if ($blEndTran == true) {

                $this->endTransaction();
            }

            return true;
        } else {

            if (defined('__DB_DEBUG') && __DB_DEBUG) {

                $this->_throwErr('事务操作提交 commit');
            }
            return false;
        }
    }


    /**
     * mysqli prepare封装
     *
     * @param $sql
     * @return bool|mysqli_stmt
     */
    public function prepare($sql) {

        $linkId = $this->_getLinkId();

        $this->_objMysqliStmt = mysqli_prepare($linkId, $sql);

        if (!$this->_objMysqliStmt) {

            $this->_throwErr($sql);
        }

        if (defined('__DB_DEBUG') && __DB_DEBUG) {

            $this->_addDbHistory('Prepare Statement [SQL]: '.$sql);
        }
        return $this->_objMysqliStmt;
    }

    /**
     * 设置bind pramat参数
     *
     * @param $type
     * @param $value
     */
    public function bindParam($type, $value) {

        $this->_arrBindVal[] = $value;
        $this->_strBindTypes .= $type;

    }

    /**
     * Prepare Statement 执行
     */
    public function execute() {

        $arrBindInfo = array_merge(array($this->_strBindTypes), $this->_arrBindVal);

        call_user_func_array( array($this->_objMysqliStmt, 'bind_param'), $this->refValues($arrBindInfo));

//        $ref    = new ReflectionClass('mysqli_stmt');
//        $method = $ref->getMethod("bind_param");
//        $method->invokeArgs($this->_objMysqliStmt, $this->refValues($arrBindInfo));

//        $bindParamsMethod = new ReflectionMethod('mysqli_stmt', 'bind_param');
//        $bindParamsMethod->invokeArgs($this->_objMysqliStmt, $this->refValues($arrBindInfo));

        /* 执行statement */
        $blTmp = mysqli_stmt_execute($this->_objMysqliStmt);

        if (defined('__DB_DEBUG') && __DB_DEBUG) {

            $this->_addDbHistory('Prepare Statement [BindParam]: '.implode(';', $arrBindInfo));

            if ($blTmp) {

                $strT = 'Prepare statement [execute], Affected rows: '.mysqli_stmt_affected_rows($this->_objMysqliStmt);
                $this->_addDbHistory($strT);
            } else {

                $this->_throwErr('Prepare Statement [execute].');
            }
        }

        /* 关闭 statement */
        $blTmp = mysqli_stmt_close($this->_objMysqliStmt);

        if (defined('__DB_DEBUG') && __DB_DEBUG) {

            if ($blTmp) {

                $this->_addDbHistory('Prepare Statement [Close].');
            } else {

                $this->_throwErr('Prepare Statement [Close].');
            }

        }

    }

    /**
     * 预处理bind需要的参数数组ref
     * @param $arr
     * @return array
     */
    private function refValues($arr) {

        $refs = array();

        foreach ($arr as $key => $value) {

            $refs[$key] = &$arr[$key];
        }

        return $refs;
    }
}




?>
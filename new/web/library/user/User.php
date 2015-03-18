<?php
/**
 * Created by PhpStorm.
 * User: williamlu
 * Date: 14-8-4
 * Time: 下午12:22
 *
 * tieyou_init 项目的测试User类定义
 */

class User {

    private $_userName = "";

    private $_userPwd = "";

    private $_arrFlag = null;

    private $_dbhWrite = null;

    private $_dbhRead = null;

    //dbProxy
    private $_dbh = null;

    public function __construct($arrTmp = array()){

        $this->_userName = "defalutName";
        $this->_userPwd = "123456";

        $this->_arrFlag = $arrTmp;

        if(__DEBUG){

            var_dump($this->_arrFlag);
        }

        $this->_prepareDBH();
    }

    public function __destruct(){

//        //关闭数据库连接
//        if ($this->_dbh) {
//
//            $this->_dbh->close();
//        }
    }


    /**
     * @param $name
     * @param $value
     */
    public function __set($name,$value){
        $this->$name = $value;
    }

    /**
     * @param $name
     * @return mixed
     */
    public function __get($name){
        return $this->$name;
    }



    /**
     * 检查数据库连接句柄
     */
    private function _prepareDBH(){
        if($this->_dbhRead === null){
            $this->_dbhRead = core::db()->getConnect('INIT', __SLAVE);
        }

        if($this->_dbhWrite === null){
            $this->_dbhWrite = core::db()->getConnect('INIT', __MASTER);
        }

        if($this->_dbh === null){
            $this->_dbh = core::db()->getConnect('INIT');
        }
    }



    /**
     * @param $strName
     * @param $strPwd
     * @return null
     */
    public function addUser1($strName, $strPwd){

        $arrRes = array();

        if(!empty($strName) && !empty($strPwd)){
            $strSql = "insert into tbl_user set user_name = '".$strName."', user_pwd = '".$strPwd."'";
            $this->_dbh->query($strSql);

            if(__DEBUG){

                echo $strSql."<br/>";
                echo "insertId: ".$this->_dbh->insertId()."<br/>";
            }

            $this->_dbh->query($strSql);
            if(__DEBUG){

                echo $strSql."<br/>";
                echo "insertId: ".$this->_dbh->insertId()."<br/>";
            }

            $strSql = "select * from tbl_user";
            $objRes = $this->_dbh->query($strSql);
            while ($_row = $this->_dbh->fetchArray($objRes)) {
                $arrRes[] = $_row;
            }

            if(__DEBUG){
                echo $strSql."<br/>";;
               // print_r($arrRes);
            }

            return null;
        }else{

            return null;
        }

    }

    /**
     * @param $arrAttr
     * @return mixed
     */
    public function addUser2($arrAttr){

        if(is_array($arrAttr)){
            foreach($arrAttr as $_key => $_val){
                $_arrField[] = "`".$_key."`='".addslashes($_val)."'" ;
            }
        }

        if(__DEBUG){
            echo sprintf('INSERT INTO tbl_user SET %s', join(',', $_arrField));
            // var_dump($this->_dbhWrite->query(sprintf('INSERT INTO `user` SET %s', join(',', $_field))));
        }

        $this->_dbhWrite->query(sprintf('INSERT INTO tbl_user SET %s', join(',', $_arrField)));

        return $this->_dbhWrite->insertId();
    }


    /**
     * @param $arrAttr
     * @param bool $blCommit true 提交事务，false 回滚
     * @return null
     */
    public function addUser3($arrAttr, $blCommit = true) {

        if(is_array($arrAttr)){
            foreach($arrAttr as $_key => $_val){
                $_arrField[] = "`".$_key."`='".addslashes($_val)."'" ;
            }
        }

        if(__DEBUG){
            echo sprintf('INSERT INTO tbl_user SET %s', join(',', $_arrField));
            // var_dump($this->_dbhWrite->query(sprintf('INSERT INTO `user` SET %s', join(',', $_field))));
        }

        $this->_dbhWrite->startTransaction();

        $this->_dbhWrite->query(sprintf('INSERT INTO tbl_user SET %s', join(',', $_arrField)));
        $this->_dbhWrite->query(sprintf('INSERT INTO tbl_user SET %s', join(',', $_arrField)));
        $this->_dbhWrite->query(sprintf('INSERT INTO tbl_user SET %s', join(',', $_arrField)));

        if ($blCommit == true) {

            $this->_dbhWrite->commit();
            return $this->_dbhWrite->insertId();
        } else {

            $this->_dbhWrite->rollback();
            return null;
        }

    }




    /**
     * prepare statement DEMO
     *
     * @return null
     */
    public function addUser4() {

        /**
         * BindParam使用DEMO      ==========Start=========
         */

        $strSqlTmp = 'INSERT INTO tbl_user (user_name, user_pwd) VALUES (?, ?)';
        $this->_dbhWrite->prepare($strSqlTmp);
        $this->_dbhWrite->bindParam('s', "测试名字111");
        $this->_dbhWrite->bindParam('s', '测试密码222');
        $this->_dbhWrite->execute();

        /**
         * BindParam使用DEMO      ==========End=========
         */

    }

} 
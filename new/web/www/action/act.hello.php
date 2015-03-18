<?php
/**
 * Class act_hello
 */
class act_hello extends action{


    /**
     * 可选则定义该方法，在action之前执行，影响所有该controler的action动作
     * 例如可以放置“身份核验”方法的调用等
     */
    function runFirst(){

        require_once('function/comm/function.comm.php');
    }

    /**
     * 可选则定义该方法，在action之后执行，影响所有该controler的action动作
     * 例如可以放置日志处理提交等
     */
    function runLast(){

        require_once('function/comm/function.view.php');
    }

    /**
     * do的业务处理,默认home
     */
    function _homeAct() {

        // function.comm.php调用
        commFunc();

        /**
         * Memcache缓存多集群使用DEMO      =========Start=========
         */
        $memWWW = core::cache()->factory(); //使用缺省DEFAULT缓存集群
        $memTest = core::cache()->factory('TEST'); //使用TEST缓存集群

        $memWWW->set("tieyou_www", "~~~~~~WWW value6测试汉字~~~~ ");
        $strInitWWW = $memWWW->get("tieyou_www");

        $memTest->set("tieyou_test", "~~~~~~Test value6测试汉字~~~~ ");
        $strInitTest = $memTest->get("tieyou_test");


        $this->view->assign('strInitWWW', $strInitWWW);
        $this->view->assign('strInitTest', $strInitTest);

        /**
         * Memcache缓存多集群使用DEMO      =========End=========
         */

        /**
         * Memcached缓存多集群使用DEMO      =========Start=========
         */
        $memWWW = core::cache()->factory('DEFAULT', _CACHE_MEMCACHED); //使用缺省DEFAULT缓存集群
        $memTest = core::cache()->factory('TEST', _CACHE_MEMCACHED); //使用TEST缓存集群

        $memWWW->set("memcached_tieyou_www1", "~~~~~~memcached WWW value2测试汉字~~~~ ");
        echo 'memcached_tieyou_www1: '.$memWWW->get("memcached_tieyou_www1").'<br/>';
        $memWWW->replace("memcached_tieyou_www1", "~~~~~~memcached WWW value444444测试汉字~~~~ ");
        echo 'memcached_tieyou_www1: '.$memWWW->get("memcached_tieyou_www1").'<br/>';

        $memTest->set("memcached_tieyou_test1", "~~~~~~memcached Test value2测试汉字~~~~ ");
        echo 'memcached_tieyou_test1: '.$memTest->get("memcached_tieyou_test1").'<br/>';
        echo "<br/>";

        // Memcached setMulti/getMulti 使用DEMO
        $arrItems = array(
            'key1' => 'value1你好',
            'key2' => 'value2你坏',
            'key3' => 'value3你唉'
        );
        $memWWW->setMulti($arrItems);
        $arrResult = $memWWW->getMulti(array('key1', 'key3', 'badkey'));
        var_dump($arrResult);

        echo "<br/><br/><br/>";


        /**
         * Memcached缓存多集群使用DEMO      =========End=========
         */


        /**
         * Redis缓存使用DEMO      ==========Start=========
         */
        $redisWWW = core::cache()->factory('DEFAULT', _CACHE_REDIS); //使用缺省DEFAULT集群
        $redisTest = core::cache()->factory('TEST', _CACHE_REDIS); //使用TEST集群

        $redisWWW->set("tieyou_redis_default", "~~~~~~DEFAULT redis value2测试汉字~~~~ ");
        $strRedisInitWWW = $redisWWW->get("tieyou_redis_default");

        $redisTest->set("tieyou_redis_test", "~~~~~~Test redis value2测试汉字~~~~ ");
        $strRedisInitTest = $redisTest->get("tieyou_redis_test");

        $this->view->assign('strRedisInitWWW', $strRedisInitWWW);
        $this->view->assign('strRedisInitTest', $strRedisInitTest);

        echo "redis->sadd():";
        $redisWWW->sadd('test', 'test1');
        $redisWWW->sadd('test', 'test3');
        var_dump($redisWWW->smembers('test'));
        echo "<br/><br/><br/>";
        /**
         * Redis缓存使用DEMO      =========End=========
         */

        /**
         * File缓存使用DEMO      =========Start=========
         */
        $fileWWW = core::cache()->factory('DEFAULT', _CACHE_FILE); //使用缺省DEFAULT缓存配置
        $fileTest = core::cache()->factory('TEST', _CACHE_FILE); //使用TEST缓存配置

        $fileWWW->set("file_www","~~~~~~file value6测试汉字~~~~ ");
        $strFileWWW = $fileWWW->get("file_www");
        echo "FILE_CACHE_WWW: ".$strFileWWW."<br/>";


        $fileTest->set("file_test","~~~~~~file Test value6测试汉字~~~~ ");
        $strFileTest = $fileTest->get("file_test");
        echo "FILE_CACHE_TEST: ".$strFileTest."<br/>";
        echo "<br/><br/><br/>";

        /**
         * File缓存使用DEMO      =========End=========
         */




        /**
         * Httpsqs队列使用DEMO      ==========Start=========
         */
        $objSQS = core::queue()->factory(); //使用缺省DEFAULT队列配置
        $objSQS->put('DEFAULT_QUEUE', 'HTTPSQS_DEFAULT_QUEUE_VALUE1测试汉字');
        $objSQS->put('DEFAULT_QUEUE', 'HTTPSQS_DEFAULT_QUEUE_VALUE2测试汉字');
        echo "DEFAULT_QUEUE: ".$objSQS->get('DEFAULT_QUEUE')."<br/>";
        echo "DEFAULT_QUEUE: ".$objSQS->get('DEFAULT_QUEUE')."<br/>";
        echo "DEFAULT_QUEUE_STATUS: ".$objSQS->status('DEFAULT_QUEUE')."<br/>";

        $objSQS = core::queue()->factory('TEST'); //使用缺省TEST队列配置
        $objSQS->put('TEST_QUEUE', 'HTTPSQS_TEST_QUEUE_value1测试汉字');
        $objSQS->put('TEST_QUEUE', 'HTTPSQS_TEST_QUEUE_value2测试汉字');
        echo "TEST_QUEUE: ".$objSQS->get('TEST_QUEUE')."<br/>";
        echo "TEST_QUEUE: ".$objSQS->get('TEST_QUEUE')."<br/>";
        echo "TEST_QUEUE: ".$objSQS->status('TEST_QUEUE')."<br/>";
        echo "<br/><br/><br/>";
        /**
         * Httpsqs队列使用DEMO      ==========End=========
         */

        /**
         * Redis队列使用DEMO      ==========Start=========
         */
        $objReidsQueue = core::queue()->factory('DEFAULT', _QUEUE_REDIS); //使用缺省DEFAULT队列配置
        $objReidsQueue->put('DEFAULT_QUEUE', 'REDIS_DEFAULT_QUEUE_VALUE1测试汉字');
        $objReidsQueue->put('DEFAULT_QUEUE', 'REDIS_DEFAULT_QUEUE_VALUE2测试汉字');
        echo "DEFAULT_QUEUE lsize:".$objReidsQueue->lsize('DEFAULT_QUEUE')."<br/>";
        echo "DEFAULT_QUEUE: ".$objReidsQueue->get('DEFAULT_QUEUE')."<br/>";
        echo "DEFAULT_QUEUE: ".$objReidsQueue->get('DEFAULT_QUEUE')."<br/>";
        echo "DEFAULT_QUEUE lsize:".$objReidsQueue->lsize('DEFAULT_QUEUE')."<br/>";

        $objReidsQueue = core::queue()->factory('TEST', _QUEUE_REDIS); //使用缺省DEFAULT队列配置
        $objReidsQueue->put('TEST_QUEUE', 'REDIS_TEST_QUEUE_VALUE1测试汉字');
        $objReidsQueue->put('TEST_QUEUE', 'REDIS_TEST_QUEUE_VALUE2测试汉字');
        echo "TEST_QUEUE: ".$objReidsQueue->get('TEST_QUEUE')."<br/>";
        echo "TEST_QUEUE: ".$objReidsQueue->get('TEST_QUEUE')."<br/>";

        echo "<br/><br/><br/>";

        /**
         * Redis队列使用DEMO      ==========End=========
         */

        /**
         * RabbitMQ队列使用DEMO      ==========Start=========
         */
//        $objRabbitMQ = core::queue()->factory('DEFAULT', _QUEUE_RABBITMQ); //使用缺省DEFAULT队列配置
//        $objRabbitMQ->put('DEFAULT_QUEUE', 'RABBITMQ_DEFAULT_QUEUE_VALUE1测试汉字');
//        $objRabbitMQ->put('DEFAULT_QUEUE', 'RABBITMQ_DEFAULT_QUEUE_VALUE2测试汉字');
//        echo "DEFAULT_QUEUE: ".$objRabbitMQ->get('DEFAULT_QUEUE')."<br/>";
//        echo "DEFAULT_QUEUE: ".$objRabbitMQ->get('DEFAULT_QUEUE')."<br/>";
//
//        $objRabbitMQ = core::queue()->factory('TEST', _QUEUE_RABBITMQ); //使用缺省TEST队列配置
//        $objRabbitMQ->put('TEST_QUEUE', 'RABBITMQ_TEST_QUEUE_value1测试汉字');
//        $objRabbitMQ->put('TEST_QUEUE', 'RABBITMQ_TEST_QUEUE_value2测试汉字');
//        echo "TEST_QUEUE: ".$objRabbitMQ->get('TEST_QUEUE')."<br/>";
//        echo "TEST_QUEUE: ".$objRabbitMQ->get('TEST_QUEUE')."<br/>";
//        echo "<br/><br/><br/>";
        /**
         * RabbitMQ队列使用DEMO      ==========End=========
         */

	}

    /**
     * 新增用户1
     */

    public function _addUser1Act(){

        $strUserName = $_GET["u"];
        if(!$strUserName){
            $strUserName = "测试1";
        }
        // autoload 方式加载类
        $objUser1 = new User();
        $objUser1->addUser1($strUserName, "测试密码.$strUserName");

    }

    /**
     * 新增用户2
     */
    public function _addUser2Act(){
        //require_once "library/user/class.user.php";
        //$objUser = new user();
        $objUser = core::Singleton("library.user.User?blTmp=true&aaaa=22222");
        $arrUser = array();
        $arrUser['user_name'] = "test6";
        $arrUser['user_pwd'] = "test6Pwd";

        print_r($objUser->addUser2($arrUser));




        // autoload 方式加载类
        $arrTmp['bbbb'] = 'adsfasdf';
        $objUser1 = new User($arrTmp);
        $arrUser1 = array();
        $arrUser1['user_name'] = "test6222";
        $arrUser1['user_pwd'] = "test6Pwd222";

        print_r($objUser1->addUser2($arrUser1));

        exit;
    }

    /**
     * 新增用户3 -- 事务
     */
    public function _addUser3Act(){

        // autoload 方式加载类
        $objUser1 = new User();
        $arrUser1 = array();
        $arrUser1['user_name'] = "test12345";
        $arrUser1['user_pwd'] = "test6Pwd12345";

        print_r($objUser1->addUser3($arrUser1, true));

        print_r($objUser1->addUser3($arrUser1, false));

        exit;
    }


    /**
     * 新增用户4 -- BindParam
     */
    public function _addUser4Act(){

        // autoload 方式加载类
        $objUser1 = new User();

        $objUser1->addUser4();

        exit;
    }
}

?>
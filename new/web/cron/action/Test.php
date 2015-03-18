<?php
/**
 * Created by PhpStorm.
 * User: williamlu
 * Date: 14-8-25
 * Time: 下午4:12
 *
 * example: php cronIndex.php Test tttt t1=111 t2=222
 * @version 1.0
 */
class Test {
    public function tttt($t1, $t2){

        echo $t2.$t1;


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
         * Redis缓存使用DEMO      ==========Start=========
         */
        $redisWWW = core::cache()->factory('DEFAULT', _CACHE_REDIS); //使用缺省DEFAULT集群
        $redisTest = core::cache()->factory('TEST', _CACHE_REDIS); //使用TEST集群
        $redisWWW->set("tieyou_redis_default","~~~~~~DEFAULT redis value2测试汉字~~~~ ");
        $strRedisInitWWW = $redisWWW->get("tieyou_redis_default");
        $redisTest->set("tieyou_redis_test","~~~~~~Test redis value2测试汉字~~~~ ");
        $strRedisInitTest = $redisTest->get("tieyou_redis_test");

        echo 'strRedisInitWWW: '.$strRedisInitWWW;
        echo 'strRedisInitTest: '.$strRedisInitTest;
        /**
         * Redis缓存使用DEMO      =========End=========
         */




        /**
         * Memcache缓存多集群使用DEMO      =========Start=========
         */
        $memWWW = core::cache()->factory('DEFAULT'); //使用缺省DEFAULT缓存集群
        $memTest = core::cache()->factory('TEST'); //使用TEST缓存集群

        $memWWW->set("tieyou_www","~~~~~~WWW value6测试汉字~~~~ ");
        $strInitWWW = $memWWW->get("tieyou_www");

        $memTest->set("tieyou_test","~~~~~~Test value6测试汉字~~~~ ");
        $strInitTest = $memTest->get("tieyou_test");

        echo 'strInitWWW: '.$strInitWWW;
        echo 'strInitTest: '.$strInitTest;

        /**
         * Memcache缓存多集群使用DEMO      =========End=========
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
     *
     * php cronIndex.php Test addUser1
     */
    public function addUser1(){

        // autoload 方式加载类
        $objUser1 = new User();
        $objUser1->addUser1("测试1", "测试密码1");
        exit;

    }
}

?>

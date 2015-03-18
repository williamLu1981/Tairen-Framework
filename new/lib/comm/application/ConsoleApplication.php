<?php

require_once(dirname(__FILE__) . '/intface/intface.application.php');

class ConsoleApplication extends iApplication {

    public function runFirst() {

    }

    public function runLast() {

    }

    public function initialize() {

        $this->appRoot = dirname($_SERVER['SCRIPT_FILENAME']) . "/";
        ini_set('include_path', ini_get('include_path') . _PATH_SEPARATOR . $this->appRoot);

        @set_magic_quotes_runtime(false); //加了个@，新版本php中废弃了此函数
        $this->config = core::Singleton('comm.application.config.Config');

        core::Singleton('comm.log.Log');

    }
    
    public function dispatch() {

        $this->runFirst();  //application的runFirst
        $arg = $_SERVER['argv'];
        array_shift($arg);
        $fileName = _APP_ROOT.'/cron/action/'.$arg[0].'.php';
        require_once $fileName;
        
        $className = array_shift($arg);
        $action_class= new $className;
        
        $methodName = array_shift($arg);
        
        $args = self::getArg($arg);
        
        $Reflection=new ReflectionMethod($action_class, $methodName);
        $methods = $Reflection->getParameters();
        
        $args = self::getInvokeArgs($args, $methods);
        $Reflection->invokeArgs($action_class, $args);

        $this->runLast();
    }
    
    /**
     * 按变量顺序,组装反射用数组
     * @param type $args
     * @param type $methods
     * @return type
     */
    private function getInvokeArgs($args,$methods){

        $result = array();
        foreach($methods as $value){

            if (isset($args[$value->name])) {

                $result[$value->name] = $args[$value->name];
            }
        }
        return $result;
    }

    /**
     * 拆分传入参数为数组
     * @param type $arg
     * @return type
     */
    private function getArg($arg){

        //拆分传入的字符串
        $args = array();
        foreach ($arg as $value) {

            $v = explode( '=',$value);
            $args[$v[0]] = $v[1];
        }
        return $args;
    }
}

?>
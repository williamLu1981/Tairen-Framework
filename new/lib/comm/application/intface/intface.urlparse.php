<?php

abstract class urlparse {

    var $paramsName	= 'param';
    var $urlParams 	= null;
    var $do			= '';
    var $action		= '';
    var $defaultDo	= 'home';
    var $defaultAction='index';
    var $pLastRegExp 	= '/\.(html|htm)$/is';
    var $pFirstRegExp = '/^[\/]*/is';
    var $actionPrefix = '/';
    var $doPrefix		= '-';
    var $paramsPrefix = '-';
    var $config		= null;
    var $resuest	= null;
    var $actionList = array();
    var $extParams = null;

    public function __construct() {

        //$this->initialize();
    }

    public function initialize() {

        $this->config = core::Singleton('comm.application.config.Config');
        $this->urlParams = $_GET[$this->paramsName];
        $this->prepareParms();
        $this->parse();
    }

    /**
     *
     * 获得action名和do名
     */
    public function parse() {

        $_pos = strpos($this->urlParams, $this->actionPrefix);
        if ( $_pos === false) {

            $this->parseAction($this->urlParams);
            $doParams = '';
        } else {

            $_action = substr($this->urlParams, 0, $_pos);
            $this->parseAction($_action);
            $doParams= substr($this->urlParams, $_pos+1, strlen($this->urlParams));
        }

        //处理扩展参数
        $this->parseDo($doParams);
    }

    /**
     * 确认action名是否在actionList中定义，并设置
     *
     * @param $actionParams action名
     * @return bool
     */
    private function parseAction($actionParams) {

        if (!in_array($actionParams, $this->actionList)) {

            $this->action = $this->defaultAction;
        } else {

            $this->action = $actionParams;
            $ret = true;
        }

        $this->do = $this->defaultDo;

        return $ret;
    }


    /**
     *
     * 处理action的扩展参数，例如：?param=home-aaa-bbb/home.html&a=1&b=2  -->  扩展参数为数组（aaa，bbb）
     *
     * @param $doParams
     */
    private function parseDo($doParams) {

        $doStr = preg_replace('/' . preg_quote($this->doPrefix) . '(.*)$/is', '', $doParams);

        $paramsStr = preg_replace('/^([^' .preg_quote($this->doPrefix) . ']*)' . preg_quote($this->doPrefix) . '/is', '', $doParams);

        $this->extParams = @split($this->paramsPrefix, $paramsStr);

        $_classFileName = sprintf("www/%s/req.%s.php", $this->config->requestFix ,$this->action);

        $_className = sprintf("req_%s",$this->action);

        if (file_exists($_classFileName)) {

            require_once($_classFileName);
            if (class_exists($_className)) {

                $this->resuest = new $_className();
                $this->resuest->setParent($this);
                $this->resuest->prepare($doStr);
                $this->resuest->extParams = $this->resuest->params->extParams;

            } else {

                die("在文件 $_classFileName 中，类 $_className 没有被定义");
            }
        } else {

            die("参数类 $_classFileName 不存在");
        }
    }

    public function getRequest() {

        return $this->resuest;
    }

    /**
     * 获得action和do的信息窜，并执行action前的预处理任务
     */
    protected function prepareParms() {

        if ($this->pLastRegExp)
            $this->urlParams = preg_replace($this->pLastRegExp, '', $this->urlParams);
        if ($this->pFirstRegExp)
            $this->urlParams = preg_replace($this->pFirstRegExp, '', $this->urlParams);

        if (method_exists($this, 'runFirst')) {

            $this->runFirst();
        }
    }

    abstract  function runFirst();

    function addAction($action) {

        if (is_array($action)) {

            array_merge($this->actionList, $action);
        } else {

            $this->actionList[] = $action;
        }
    }

    public function setParamsName($name) {

        $this->paramsName = $name;
    }

    public function getAction() {

        return $this->action;
    }

    public function getDo() {

        return $this->do;
    }

    public final function __get($name) {
        return $this->$name;
    }
}
?>
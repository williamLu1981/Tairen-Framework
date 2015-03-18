<?php
/**
 * Created by PhpStorm.
 * User: williamlu
 * Date: 14-8-5
 * Time: 上午11:40
 * 核心类,提供对全站各种类库的访问和调用
 * @version 1.0
 */
class Core {

    //类实例数组
    private static $_OBJECT = array();

    //当前类库所在的目录，使用RealPath获取，在一些PHP的配置上可能会有问题出现
    private static $_LIB_ROOT = '';

    //一些常用类库的简单表示
    private static $_FAVORITE_FIX = null;

    /**
     * 加入缺省类语
     * @param void
     * @return void
     */
    public static function loadDefFavortie() {

        if (self::$_FAVORITE_FIX === null) {

            self::$_LIB_ROOT = preg_replace('/comm[\/\\\]core$/is', '', dirname(__FILE__));

            //初始化常用对像
            require_once (self::$_LIB_ROOT . 'comm/config/core.php');
            if (is_array($_CORE_FAV_LIB)) {

                self::$_FAVORITE_FIX = $_CORE_FAV_LIB;
            } else {

                self::$_FAVORITE_FIX = array();
            }
        }
    }

    /**
     * 加载类库短语配置
     *
     * @param $name String or Array 短语配置名称
     * @param $path String 类库所在目录, 默认为空
     * @param $className String 类名称,默认为空
     * @param $defaultArg mixed 缺省的参数，默认为空
     * @return void
     */
    public static function addFavorite($name, $path = null, $className = null, $defaultArg = null) {

        if (is_array($name)) {

            foreach ( $name as $_preFix => $value ) {

                self::addFavoriteOne($_preFix, $value['PATH'], $value['CLASS'], $value['DEF_ARG']);
            }
        } else {

            self::addFavoriteOne($name, $path, $className, $defaultArg);
        }
    }

    /**
     * 增加一条类库短语配置
     *
     * @param $name String 短语配置名称
     * @param $path String 类库所在目录, 默认为空
     * @param $className String 类名称,默认为空
     * @param $defaultArg mixed 缺省的参数，默认为空
     * @return void
     */
    private static function addFavoriteOne($name, $path, $className, $defaultArg = null) {

        self::$_FAVORITE_FIX[$name] = array('PATH' => $path, 'CLASS' => $className, 'DEF_ARG' => $defaultArg);
    }

    /**
     * 函数重载用，通过类 $c->memcahe->set($key, $value); 的方式对全站的对像进行访问
     * 支持对类经过参数进行唯一性判断
     *
     * @param $objName String 对像名称
     * @return Object
     */
    public function __get($objName) {

        return self::getInstance($objName);
    }

    /**
     * 函数重载用，通过类 $c->memmcahe('club')->set($key, $value); 的方式对全站的对像进行访问
     * 支持对类经过参数进行唯一性判断
     *
     * @param $objName String 对像名称
     * @param $objArg	String 函数参数
     * @return Object
     */
    public function __call($objName, $objArg = null) {

        return $this->getInstance($objName, $objArg);
    }

    /**
     * 函数重载用，通过类 $c->memmcahe('club')->set($key, $value); 的方式对全站的对像进行访问
     * 支持对类经过参数进行唯一性判断
     *
     * @param $objName String 对像名称
     * @param $objArg	String 函数参数
     * @return Object
     */
    public static function __callStatic($objName, $objArg = null) {

        return self::getInstance($objName, $objArg);
    }

    /**
     * 创建对像
     *
     * @param $objName String 对像名称
     * @param $funArg String 参数
     * @return Object
     */
    private static function getInstance($objName, $funArg = null) {

        self::loadDefFavortie();

        $lowerObjName = strtolower($objName);
        if (isset(self::$_FAVORITE_FIX[$lowerObjName])) {
            //处理类库短语

            return self::favoriteProcess($objName, $funArg);
        } else {

            //die("function $objName not exists");
        }
    }

    /**
     * 获取对像
     *
     *
     */
    private static function instanceOfFileName($fileName, $className, $funArg=null) {
        $_fix = (is_array($funArg) && !empty($funArg)) ? serialize($funArg) : '';
        $_fix = $fileName . $_fix;

        if (self::registry($_fix)) {

            return self::register($_fix);
        } else {

            require_once ($fileName);

            if (class_exists($className)) {

                $obj = new $className($funArg);
                self::register($_fix, $obj);

                return $obj;
            } else {
                die("No classFile '{$fileName}'　exists or no define class '{$className}' !!!");
            }
        }
    }

    /**
     * 处理短语
     *
     * @param $objName String 类名
     * @param $funArg Mixed 参数
     * @return Object
     */
    private static function favoriteProcess($objName, $funArg=null){

        $fileName = self::$_FAVORITE_FIX[$objName]['PATH'];

        if($funArg === null){
            $funArg = self::$_FAVORITE_FIX[$objName]['DEF_ARG'];
        }
        return self::instanceOfFileName($fileName, self::$_FAVORITE_FIX[$objName]['CLASS'], $funArg);
    }

    /**
     * 注册一个对像实例
     *
     * @param $key String 对像的键值
     * @param $obj Objetc 对像实例
     * @return Boolean
     */
    public static function register($key, $obj = null) {

        $md5 = md5($key);

        if (is_object($obj)) {
            self::$_OBJECT[$md5] = array('class' => $obj, 'fix' => get_class($obj));
        } else {
            return self::$_OBJECT[$md5]['class'];
        }
    }

    /**
     * 检查指定 key 的对像实例是否存在
     *
     * @param $key String 要检查对像的键值
     * @return Boolean
     */
    public static function registry($key) {

        $md5 = md5($key);
        return (isset(self::$_OBJECT[$md5]) && is_object(self::$_OBJECT[$md5]['class']));
    }

    /**
     * 解析对应Module参数
     *
     * @param   array
     * @return  array
     */
    private static function parseDsn($dsn = null) {
        preg_match('/([^?]*)\?{0,1}(.*)/i', $dsn, $_tmpUrl);
        $_tmpUrl[1] = str_replace('.', '/', $_tmpUrl[1]);
        $_ret['fileName'] = preg_replace('/\/([^\/]*)$/i', '/\\1.php', $_tmpUrl[1]);
//        echo $_ret['fileName']."<br>";
        $_ret['className'] = preg_replace('/(.*)\/([^\/]*)$/i', '\\2', $_tmpUrl[1]);
        parse_str($_tmpUrl[2], $_ret['arg']);
//        print_r($_ret);

        return $_ret;
    }

    /**
     * 获取一单例对像
     *
     * @param String $dsn
     * @return mixed
     */
    public static function Singleton($dsn) {
        if (empty($dsn)) {

            return null;
        }

        $_arg = self::parseDsn($dsn);

        return self::instanceOfFileName($_arg['fileName'], $_arg['className'], $_arg['arg']);
    }

    /**
     * 获取一对像
     *
     * @param String $dsn
     * @return mixed
     */
    public static function Instance($dsn) {

        if (empty($dsn)) {

            return null;
        }

        $_arg = self::parseDsn($dsn);

        require_once ($_arg['fileName']);
        if (class_exists($_arg['className'])) {

            $obj = new $_arg['className']();
            return $obj;
        } else {

            die("No classFile '{$_arg[fileName]}'　exists or no define class '{$_arg[className]}' !!!");
        }
    }

    public static function toString() {

        echo "<pre>";
        echo '_OBJECT<br>';
        print_r(core::$_OBJECT);
    }


    /**
     * 用户类autoload函数定义
     *
     * @param $strClassName
     */
    public static function autoload($strClassName) {

        if (isset($GLOBALS['AUTOLOAD'][$strClassName])) {

            if (file_exists(_APP_ROOT.'library/'.$GLOBALS['AUTOLOAD'][$strClassName])) {

                require_once ('library/'.$GLOBALS['AUTOLOAD'][$strClassName]);
            } else {

                exit('autoload 需要加载的类文件不存在：'._APP_ROOT.'library/'.$GLOBALS['AUTOLOAD'][$strClassName]);
            }

        } else {

            $strClassFilePath = self::searchClassFilePath(_APP_ROOT.'library/', $strClassName);

            if ($strClassFilePath) {

                require_once $strClassFilePath;
            } else {

                exit('autoload 需要加载的文件配置不存在：'.$strClassName);
            }

        }

    }

    /**
     * 框架类autoload函数定义
     */
    public static function core_autoload() {

        require_once(_LIB_ROOT.'comm/db/Mysql.php');
        require_once(_LIB_ROOT.'comm/db/DBProxy.php');

    }


    /**
     * 遍历Library目录，寻找需要require的类文件
     *
     * @param $strPath
     * @param $strClassName
     * @return bool|string
     */
    public static function searchClassFilePath($strPath, $strClassName) {

        $strFileName = $strClassName.'.php';
        $strCurrentPath = opendir($strPath);    //opendir()返回一个目录句柄,失败返回false

        while(($fileTmp = readdir($strCurrentPath)) !== false) {    //readdir()返回打开目录句柄中的一个条目
            $strSubPath = $strPath . DIRECTORY_SEPARATOR . $fileTmp;    //构建子目录路径
            if($fileTmp == '.' || $fileTmp == '..') {

                continue;
            } else if(is_dir($strSubPath)) {    //如果是目录,进行递归

                $strClassFilePath = self::searchClassFilePath($strSubPath, $strClassName);
                if ($strClassFilePath) {
                    return $strClassFilePath;
                }

            } else if($fileTmp == $strFileName) {

                return $strPath.'/'.$fileTmp;

            }
        }

        return false;

    }

}

spl_autoload_register(array('Core', 'core_autoload'));
spl_autoload_register(array('Core', 'autoload'));

?>
<?php
/*
在所需要防护的页面加入代码 require_once('SecFilter.php');就可以做到页面防注入、跨站。
如果想整站防护，就在网站的一个公用文件中，如数据库链接文件config.inc.php中，添加require_once('SecFilter.php');来调用本代码。
by Information Security Department
*/
//Xss Payload
$url_arr=array(
    'xss'=>"\\=\\+\\/v(?:8|9|\\+|\\/)|\\%0acontent\\-(?:id|location|type|transfer\\-encoding)",
);

//Xss Payload
$args_arr=array(
    'xss'=>"[\\'\\\"\\;\\*\\<\\>].*\\bon[a-zA-Z]{3,15}[\\s\\r\\n\\v\\f]*\\=|\\b(?:expression)\\(|\\<script[\\s\\\\\\/]|\\<\\!\\[cdata\\[|\\b(?:eval|alert|prompt|onabort|onactivate|onafterprint|onafterupdate|onbeforeactivate|onbeforecopy|onbeforecut|onbeforedeactivate|onbeforeeditfocus|onbeforepaste|onbeforeprint|onbeforeunload|onbeforeupdate|onblur|onbounce|oncellchange|onchange|onclick|oncontextmenu|oncontrolselect|oncopy|oncut|ondataavailable|ondatasetchanged|ondatasetcomplete|ondblclick|ondeactivate|ondrag|ondragend|ondragenter|ondragleave|ondragover|ondragstart|ondrop|onerror|onerrorupdate|onfilterchange|onfinish|onfocus|onfocusin|onfocusout|onhelp|onkeydown|onkeypress|onkeyup|onlayoutcomplete|onload|onlosecapture|onmousedown|onmouseenter|onmouseleave|onmousemove|onmouseout|onmouseover|onmouseup|onmousewheel|onmove|onmoveend|onmovestart|onpaste|onpropertychange|onreadystatechange|onreset|onresize|onresizeend|onresizestart|onrowenter|onrowexit|onrowsdelete|onrowsinserted|onscroll|onselect|onselectionchange|onselectstart|onstart|onstop|onsubmit|onunload|msgbox)\\s*\\(|url\\((?:\\#|data|javascript)",

//Sql Injection
    'sql'=>"[^\\{\\s]{1}(\\s|\\b)+(?:select\\b|update\\b|insert(?:(\\/\\*.*?\\*\\/)|(\\s)|(\\+))+into\\b).+?(?:from\\b|set\\b)|[^\\{\\s]{1}(\\s|\\b)+(?:create|delete|and|drop|truncate|rename|desc)(?:(\\/\\*.*?\\*\\/)|(\\s)|(\\+))+(?:table\\b|from\\b|database\\b)|into(?:(\\/\\*.*?\\*\\/)|\\s|\\+)+(?:dump|out)file\\b|\\bsleep\\([\\s]*[\\d]+[\\s]*\\)|benchmark\\(([^\\,]*)\\,([^\\,]*)\\)|(?:declare|set|select)\\b.*@|union\\b.*(?:select|all)\\b|(?:select|update|and|insert|create|delete|drop|grant|truncate|rename|exec|desc|from|table|database|set|where)\\b.*(charset|ascii|bin|char|uncompress|concat|concat_ws|conv|export_set|hex|instr|left|load_file|locate|mid|sub|substring|oct|reverse|right|unhex)\\(|(?:master\\.\\.sysdatabases|msysaccessobjects|msysqueries|sysmodules|mysql\\.db|sys\\.database_name|information_schema\\.|sysobjects|sp_makewebtask|xp_cmdshell|sp_oamethod|sp_addextendedproc|sp_oacreate|xp_regread|sys\\.dbms_export_extension)",

//File Inclusion Vulnerability
    'other'=>"\\.\\.[\\\\\\/].*\\%00([^0-9a-fA-F]|$)|%00[\\'\\\"\\.]");

$referer=empty($_SERVER['HTTP_REFERER']) ? array() : array($_SERVER['HTTP_REFERER']);
$query_string=empty($_SERVER["QUERY_STRING"]) ? array() : array($_SERVER["QUERY_STRING"]);

sec_check_data($query_string,$url_arr);
sec_check_data($_GET,$args_arr);
sec_check_data($_POST,$args_arr);
sec_check_data($_COOKIE,$args_arr);
sec_check_data($referer,$args_arr);


function sec_check_data($array,$v) {
//如果是数组，遍历数组，递归调用
    if (is_array ( $array )) {
        foreach ( $array as $k=>$value) {
            $array [$k] = sec_check_data ( $value,$v );
        }
    } else if (is_string ( $array )) {
        //使用addslashes函数及特征来处理
        $array = sec_check(sec_addslashes_deep($array),$v);
        //整型过滤函数
    } else if (is_numeric ( $array )) {
        $array = intval ( $array );
    }
}

function sec_check($str,$v)
{
    foreach($v as $key=>$value)
    {
        if (preg_match("/".$value."/is",$str)==1||preg_match("/".$value."/is",urlencode($str))==1)
        {
            //print "您的提交带有不合法参数,谢谢合作";
            exit();
        }
    }
}

function sec_addslashes_deep($value,$htmlspecialchars=false)
{
    if (empty($value))
    {
        return $value;
    }
    else
    {
        if(is_array($value))
        {
            foreach($value as $key => $v)
            {
                unset($value[$key]);

                if($htmlspecialchars==true)
                {
                    $key=addslashes(htmlspecialchars($key));
                }
                else{
                    $key=addslashes($key);
                }

                if(is_array($v))
                {
                    $value[$key]=sec_addslashes_deep($v);
                }
                else{
                    if($htmlspecialchars==true)
                    {
                        $value[$key]=addslashes(htmlspecialchars($v));
                    }
                    else{
                        $value[$key]=addslashes($v);
                    }
                }
            }
        }
        else{
            if($htmlspecialchars==true)
            {
                $value=addslashes(htmlspecialchars($value));
            }
            else{
                $value=addslashes($value);
            }
        }
        return $value;
    }
}
?>
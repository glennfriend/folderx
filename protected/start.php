<?php

/**
 *  驗證進入者的 ip 是否在 allow ip array 之中
 */
function isAllowIp( $ipArray )
{
    if( !is_array($ipArray) ) {
        return false;
    }

    $isAllowDebug = false;
    foreach( $ipArray as $allowIp ) {
        $len = strlen($allowIp);
        if( substr($_SERVER['REMOTE_ADDR'],0,$len) === $allowIp ) {
            $isAllowDebug = true;
            break;
        }
    }
    return $isAllowDebug;
}


/**
 *  產生網址, 本是屬於 bridge 的部份
 *  因為使用率極高, 所以設定成 helper function
 */
function url( $route, $params=null )
{
    if ( is_array($params) ) {
        return Yii::app()->createUrl( $route, $params );
    }
    return Yii::app()->createUrl( $route );
}


/**
 *  escape output
 *
 *  @link http://www.smarty.net/manual/en/language.modifier.escape.php
 *        escape (Smarty online manual)
 *        by 2012-01-04
 *  @param string
 *  @param html|htmlall|url|quotes|hex|hexentity|javascript
 *  @return string
 *
 */
function escape( $string, $esc_type = "html", $char_set = 'UTF-8' )
{
    switch ($esc_type) {
        case 'html':
            return htmlspecialchars($string, ENT_QUOTES, $char_set);

        case 'htmlall':
            return htmlentities($string, ENT_QUOTES, $char_set);

        case 'url':
            return rawurlencode($string);

        case 'urlpathinfo':
            return str_replace('%2F','/',rawurlencode($string));

        case 'quotes':
            // escape unescaped single quotes
            return preg_replace("%(?<!\\\\)'%", "\\'", $string);

        case 'hex':
            // escape every character into hex
            $return = '';
            for ($x=0; $x < strlen($string); $x++) {
                $return .= '%' . bin2hex($string[$x]);
            }
            return $return;

        case 'hexentity':
            $return = '';
            for ($x=0; $x < strlen($string); $x++) {
                $return .= '&#x' . bin2hex($string[$x]) . ';';
            }
            return $return;

        case 'decentity':
            $return = '';
            for ($x=0; $x < strlen($string); $x++) {
                $return .= '&#' . ord($string[$x]) . ';';
            }
            return $return;

        case 'javascript':
            // escape quotes and backslashes, newlines, etc.
            return strtr($string, array('\\'=>'\\\\',"'"=>"\\'",'"'=>'\\"',"\r"=>'\\r',"\n"=>'\\n','</'=>'<\/'));

        case 'mail':
            // safe way to display e-mail address on a web page
            return str_replace(array('@', '.'),array(' [AT] ', ' [DOT] '), $string);

        case 'nonstd':
           // escape non-standard chars, such as ms document quotes
           $_res = '';
           for($_i = 0, $_len = strlen($string); $_i < $_len; $_i++) {
               $_ord = ord(substr($string, $_i, 1));
               // non-standard char, escape it
               if($_ord >= 126){
                   $_res .= '&#' . $_ord . ';';
               }
               else {
                   $_res .= substr($string, $_i, 1);
               }
           }
           return $_res;

        default:
            return $string;
    }
}


/**
 *  cc ccHelper function call
 *
 *  example:
 *      cc('date',   time()       );
 *      cc('escape', $articleText );
 *
 *  @param helper function name
 *  @param param2
 *  @param param3
 *  @param param4
 *  @param param5
 *  @return maybe have maybe not have
 */
function cc()
{
    $numArgs = func_num_args();
    $args    = func_get_args();
    $func    = $args[0];

    include_once('ccHelper/'. $func .'.php');
    $functionName = 'ccHelper_'. $func;

    switch( $numArgs )
    {
        case 1: return $functionName();                                         exit;
        case 2: return $functionName( $args[1] );                               exit;
        case 3: return $functionName( $args[1], $args[2] );                     exit;
        case 4: return $functionName( $args[1], $args[2], $args[3]);            exit;
        case 5: return $functionName( $args[1], $args[2], $args[3], $args[4] ); exit;
        default:
            return '[cc error]';
    }
}


/**
 *  I18n translation (Languages)
 *
 *  example:
 *      lgg('description');           // 描述
 *      lgg('welcome',20,'vivian');   // 歡迎 %1 歲的 %2 => 歡迎 20 歲的 vivian
 *
 *  @param message
 *  @return string
 */
function lgg()
{
    $numArgs = func_num_args();
    $args    = func_get_args();
    $message = $args[0];

    if( count($args)>=2 ) {
        unset($args[0]);
        $params = Array();
        $i=0;
        foreach( $args as $arg ) {
            $i++;
            $params['%'.$i] = $arg;
        }
        return Yii::t('lang',$message,$params);
    }
    else {
        return Yii::t('lang',$message);
    }

    //return I18n::t($message);
}


/**
 *  get application config file name or die()
 */
function getApplicationConfig()
{
    $baseConfigFileName = realpath( APPLICATION_BASE_PATH . '/protected/config/base.php' );
    if (!$baseConfigFileName) {
        die('please copy the template file "base.php" and edit it.');
    }

    $configFileName = realpath( APPLICATION_BASE_PATH . '/protected/config/'. APPLICATION_PORTAL .'.php' );
    if (!$configFileName) {
        die('please copy the template file "'. APPLICATION_PORTAL .'.php" and edit it.');
    }

    $config = require( $baseConfigFileName );
    require( $configFileName );
    return $configSetting( $config );
};


function pr( $data, $showName='' )
{
    echo '<pre style="background-color:#def;color:#000;text-align:left;font-size:10px;font-family:dina,GulimChe;">';
    if ( $showName ) {
        echo $showName . ' = ';
    }
    print_r( $data );
    echo "</pre>\n";
}


/**
 *  init
 */
$init = function()
{
    //
    // include global setting
    //
    $configFile = realpath(__DIR__.'/config/config.php' ) or die('Please setting "config.php" file');
    require_once($configFile);

    //
    // session start
    // session life time setting
    //
    $lifeTime = 2*3600;     // 3600  = 1 hour
                            // 86400 = 24*3600 = 1 day

    session_name(APPLICATION_PRIVATE_DYNAMIC_CODE);
    session_start();
    setcookie( session_name(), session_id(), time() + $lifeTime, "/" );



    //
    // debug mode settiong
    //
    // if( isset($_SESSION['is_debug']) && true === $_SESSION['is_debug'] ) {
    //     error_reporting(E_ALL);
    //     ini_set('html_errors','On');
    //     ini_set('display_errors','On');
    //     defined('YII_DEBUG') or define('YII_DEBUG',true);
    //     // Set 1 for Only Errors
    //     // Set 2 for Error+Warnings
    //     // Set 3 for All Errors
    //     defined('YII_TRACE_LEVEL') or define('YII_TRACE_LEVEL',3);
    // }
    // else{
    //     error_reporting(E_ALL ^ E_NOTICE);
    //     ini_set('html_errors','Off');
    //     ini_set('display_errors','Off');
    // }

    error_reporting(E_ALL);
    ini_set('html_errors','On');
    ini_set('display_errors','On');
    defined('YII_DEBUG') or define('YII_DEBUG',true);
    defined('YII_TRACE_LEVEL') or define('YII_TRACE_LEVEL',3);


    //
    // include yii framework
    //
    $yiiFramework = realpath(__DIR__.'/../framework/yii.php' ) or die('can ont find framework files.');
    require_once( $yiiFramework );

};
$init();
unset($init);

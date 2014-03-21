<?php

/**
 *  對資料進行加密
 *  該加密的資料可以在 url 上傳輸
 */
class TextEncryption
{

    /**
     *  使用前必須先 Initialize
     */
    protected static $_isInit = false;

    /**
     *  include encrypt library
     */
    public function init()
    {
        if( self::$_isInit ) {
            return;
        }
        self::$_isInit = true;

        Yii::import('application.vendors.phprpc.*');
        require_once('xxtea.php');
    }

    /**
     *  text encrypt
     */
    public function encrypt( $text )
    {
        self::init();

        $text = base64_encode( xxtea_encrypt( $text, APPLICATION_PRIVATE_DYNAMIC_CODE ) );
        return strtr( $text, '+/=', '-_,');
    }

    /**
     *  text decrypt
     */
    public function decrypt( $text )
    {
        self::init();

        $text = strtr( $text, '-_,', '+/=');
        return xxtea_decrypt( base64_decode( $text ), APPLICATION_PRIVATE_DYNAMIC_CODE );
    }

}

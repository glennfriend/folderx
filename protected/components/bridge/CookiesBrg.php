<?php

class CookiesBrg
{
    /**
     *
     */
    static public function get($key)
    {
        $cookieObject = Yii::app()->request->cookies[$key];
        if( !$cookieObject ) {
            return null;
        }
        return $cookieObject->value;
    }

    /**
     *  注意 cookie 的特性, 在設定完的那一次, 無法取得該 cookie 值
     *  除非你回寫值到 $_COOKIE 之中
     */
    static public function set($key, $value, $option=array() )
    {
        $cookieObject = Yii::app()->request->cookies[$key];
        if( !$cookieObject ) {
            $cookieObject = new CHttpCookie($key, $value);
        }
        else {
            $cookieObject->value = $value;
        }

        if ( isset($option['expire']) ) {
            $cookieObject->expire = $option['expire'];
        }
    }

    /**
     *
     */
    static public function remove($key)
    {
        unset( Yii::app()->request->cookies[$key] );
    }

}

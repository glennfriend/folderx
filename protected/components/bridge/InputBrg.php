<?php

class InputBrg
{

    /**
     *  get filter value
     *  @see http://blog.gslin.org/archives/2013/12/27/4012/filter-input-escape-output/
     */
    /*
    public function strFilter( $key, $defaultValue=null )
    {
        $value = self::get( $key, $defaultValue );
        $value = iconv('UTF-8', 'UTF-8', $value);
        return trim(strip_tags($value));
    }

    public function intFilter( $key, $defaultValue=null )
    {
        return intval( self::get( $key, $defaultValue ) );
    }
    */


    /**
     *  get $_POST or $_GET value
     */
    static public function get( $key, $defaultValue=null )
    {
        if ( !self::has($key) ) {
            return $defaultValue;
        }
        return self::post($key) ?: self::query($key);
    }

    /**
     *  has input $_POST or $_GET
     */
    static public function has( $key )
    {
        return self::post(  $key ) ? true :
               self::query( $key ) ? true :
               false;
    }

    /**
     *  get $_GET value
     */
    static public function query( $key, $defaultValue=null )
    {
        return isset($_GET[$key]) ? $_GET[$key] : $defaultValue;
    }

    /**
     *  get $_POST value
     */
    static public function post( $key, $defaultValue=null )
    {
        return isset($_POST[$key]) ? $_POST[$key] : $defaultValue;
    }

    /**
     *  is post
     */
    static public function isPost()
    {
        return isset($_SERVER['REQUEST_METHOD']) && !strcasecmp($_SERVER['REQUEST_METHOD'], 'POST' );
    }

    /**
     *  get a post file or all post files
     */
    static public function files( $filename='' )
    {
        if ( $filename && isset($_FILES[$filename]) ) {
            return $_FILES[$filename];
        }
        return $_FILES;
    }

    /**
     *  is ajax
     */
    // public function isAjax()
    // {
    //     return isset($_SERVER['HTTP_X_REQUESTED_WITH']) && $_SERVER['HTTP_X_REQUESTED_WITH']==='XMLHttpRequest';
    // }

    /**
     *  is https
     */
    // public function isHttps()
    // {
    //     return !empty($_SERVER['HTTPS']) && strcasecmp($_SERVER['HTTPS'],'off');
    // }


    /**
     *  get post file
     */
    // public function file( $filename )

    /**
     *  檔案是否上傳完成
     */
    // public function hasFile( $filename )
    
}

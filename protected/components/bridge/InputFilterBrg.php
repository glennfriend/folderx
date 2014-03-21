<?php

class InputFilterBrg
{

    /* --------------------------------------------------------------------------------
        filter 文字符號 ( 單一的數字 也算是 文字符號 )
    -------------------------------------------------------------------------------- */

    /**
     *  HTML code filter
     *  @return string or empty string
     */
    static public function getText( $key, $defaultValue=null )
    {
        $value = InputBrg::get( $key, $defaultValue );
        // clean utf8 bom
        $value = iconv('UTF-8', 'UTF-8', $value );
        return trim(strip_tags( $value ));
    }

    /**
     *  english filter -> a-z A-Z
     *  @return string or empty string
     */
    static public function getEnglish( $key, $defaultValue=null )
    {
        $value = InputBrg::get( $key, $defaultValue );
        return preg_replace("/[^a-z]+/i", "", $value );
    }

    /**
     *  number filter -> 予許 0-9
     *  @return int
     */
    static public function getNumber( $key, $defaultValue=null )
    {
        $value = InputBrg::get( $key, $defaultValue );
        return preg_replace("/[^0-9]+/", "", $value );
    }

    /* --------------------------------------------------------------------------------
        filter 數值
    -------------------------------------------------------------------------------- */

    /**
     *  用於辦認使用者輸入的 數 "值"
     *  跟 "filter number" 是不一樣的
     *
     *  Ex.
     *        +200 => 200
     *      -12.34 => -12
     *     5.0E+20 => 0
     *      12a34b => 12
     *
     *  PS.
     *      5.0E+20 = 500000000000000000000
     *
     *  整數 filter -> 予許 負號 "-"
     *  @return int
     */
    static public function getInt( $key, $defaultValue=null )
    {
        return (int) InputBrg::get( $key, $defaultValue );
    }

    /**
     *  浮點數 filter -> 予許 負號 "-" 小數點 "." 
     *  @return int
     */
    static public function getFloat( $key, $defaultValue=null )
    {
        return (float) InputBrg::get( $key, $defaultValue );
    }




    
}

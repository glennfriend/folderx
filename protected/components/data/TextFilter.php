<?php

class TextFilter
{
    /**
     *  將內容轉化成為較容易搜尋的文字
     *  這些文字將會存入資料庫
     *  做為搜尋使用
     */
    function normalizedSearchText( $string )
    {
        // TODO: 再移除 重覆的字串

        $string = Ydin\Html\Filter::javascriptTags( $string );
        $string = Ydin\Html\Filter::htmlTags($string);
        $string = Ydin\Html\Convert::entitiesDecode( $string );
        $string = preg_replace( "/[^A-Za-z0-9_一-龥]/u", " ", $string );
        return    preg_replace( "/ +/", " ", $string );
    }

}


<?php

class StringHelper
{

    /**
     *  原有的 pathinfo 有中文的問題
     *  所以使用了迂迴的方式解決
     */
    function pathinfo( $filepath )
    {
        $info = array();
        $info['dirname'] = dirname($filepath);
        $info['basename'] = ltrim( substr($filepath, strrpos($filepath, '/')), '/' );

        $extension = substr(strrchr($info['basename'], '.'), 1);
        if( $extension ) {
            $info['extension'] = $extension;
        }

        $findDot = strrpos($info['basename'], '.');
        if ( $findDot ) {
            $info['filename'] = ltrim( substr($info['basename'], 0, $findDot), '/');
        }
        else {
            $info['filename'] = $info['basename'];
        }

        return $info;
    }

}


/*

$test='/home/public/game/Company.of.Heroes.2.Collectors.英雄連隊2/Company of Heroes 2';
pr(pathinfo($test));
pr(StringHelper::pathinfo($test));

$test='/home/public/game/Company.of.Heroes.2.Collectors.英雄連隊2/心心.txt';
pr(pathinfo($test));
pr(StringHelper::pathinfo($test));

$test='/home/public/game/Company.of.Heroes.2.Collectors.英雄連隊2/心心';
pr(pathinfo($test));
pr(StringHelper::pathinfo($test));
*/
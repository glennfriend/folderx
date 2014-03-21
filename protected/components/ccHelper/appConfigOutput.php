<?php

/**
 *  get all app config
 *
 *  PS. 警告!
 *      array 輸出表示為 private
 *      json  輸出表示為 public
 *      任何私有 path 可以輸出為 array
 *      但是絕不能輸出 json
 *      如有 path 路徑 (如 /var/www/project)
 *      一律在輸出成 json 之前要刪除該值!!
 *
 *  @param type "array" or "json"
 *  @return php arrayo or json string
 */
function ccHelper_appConfigOutput( $type='json' )
{
    $config = array(
        'portal'    => APPLICATION_PORTAL,
        'baseUri'   => Yii::app()->baseUrl ,
        'themeUri'  => Yii::app()->baseUrl . '/themes' ,
        'homeUri'   => APPLICATION_HOME_URI ,
        'httpHost'  => $_SERVER['HTTP_HOST'] ,
    );

    if ( 'array'===$type ) {
        return $config;
    }

    // remove private value
    // 暫無 ....

    // return
    return "var app = app || {};\n"
            . 'app.config=' . json_encode($config)
            . ";\n";
}

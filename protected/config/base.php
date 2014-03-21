<?php

return array(
    'basePath'  => __DIR__ . DIRECTORY_SEPARATOR . '..',
    'name'      => 'FolderX',
    'language'  => 'zh_tw',
    'preload'   => array('log'),
    'import'    => array(
        'application.models.*',
        'application.components.*',
        'application.components.bridge.*',
        'application.components.data.*',
        'application.components.developer.*',
        'application.components.file.*',
        'application.components.helper.*',
    ),
    'defaultController' => 'home',
    'modulePath'        => APPLICATION_BASE_PATH . '/protected/modules/' . APPLICATION_PORTAL,
    'modules'           => array(),
    // application components
    'components' => array(
        'user' => array(),
        'urlManager' => array(
            'urlFormat'      => 'path',
            'showScriptName' => false,
            'appendParams'   => false,
            'rules'=>array(),
        ),
        /*
        'db'=>array(
            'connectionString'      => 'mysql:host='. APPLICATION_DB_MYSQL_HOST .';dbname='. APPLICATION_DB_MYSQL_DB,
            'emulatePrepare'        => true,
            'username'              => APPLICATION_DB_MYSQL_USER,
            'password'              => APPLICATION_DB_MYSQL_PASS,
            'charset'               => 'utf8',
            'schemaCachingDuration' => 3600,  // cache Yii "SHOW COLUMNS FROM" and "SHOW CREATE TABLE"
        ),
        */
        'cache'=>array(
            'class'     => 'system.caching.CFileCache',
            'keyPrefix' => APPLICATION_CACKE_KEY,
        ),
        'errorHandler' => array(
            'errorAction' => '/error',
        ),
        'log'=>array(
            'class' => 'CLogRouter',
            'routes' => array(
                array(
                    'class'  => 'CFileLogRoute',
                    'levels' => 'error, warning',
                ),
                // db log
                array(
                    'class'         => 'YiiLogRouteBrg', // 'CDbLogRoute',
                    'logTableName'  => 'yii_applog',
                    'connectionID'  => 'db',
                    'levels'        => 'error, warning',
                ),
            ),
        ),
    ),

    // application-level parameters that can be accessed
    // using Yii::app()->params['paramName']
    'params' => array(),

);

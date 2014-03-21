<?php

class BaseCore
{

    static public function init()
    {
        self::LoaderInit();
    }

    /**
     *  load Zend library
     *  load Ydin library
     */
    static private function LoaderInit()
    {
        Yii::import('application.vendors.Zend.Loader.*');

        require_once 'StandardAutoloader.php';
        $loader = new Zend\Loader\StandardAutoloader(array(
            'autoregister_zf' => true,
            'namespaces' => array(
                'Ydin' => Yii::getPathOfAlias('application.vendors.Ydin'),
            ),
        ));
        $loader->register();
    }

}
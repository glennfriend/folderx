<?php

class HomeBaseController extends CController
{

    /**
     *
     */
    public $layout = '//home/layout_column';

    /**
     *
     */
    public $pageLimit = null;

    /**
     *
     */
    public function init()
    {
        parent::init();

        // UrlManager setting
        $config = cc('appConfigOutput','array');
        UrlManager::init(array(
            'baseUri'   => $config['baseUri'],
            'themeUri'  => $config['themeUri'],
        ));

        // jquery
        Yii::app()->clientScript->registerScriptFile( $config['baseUri'] . "/js/jquery/jquery-2.0.3.js" );

        // jquery qtip
        Yii::app()->clientScript->registerCssFile(    $config['baseUri'] . '/js/jquery/qtip2/jquery.qtip.css'  );
        Yii::app()->clientScript->registerScriptFile( $config['baseUri'] . '/js/jquery/qtip2/jquery.qtip.js'   );

        // Twitter Bootstrap
        Yii::app()->clientScript->registerCssFile(    $config['themeUri'] . '/bootstrap_302/dist/css/bootstrap.css'          );
        Yii::app()->clientScript->registerCssFile(    $config['themeUri'] . '/bootstrap_302/dist/css/bootstrap-theme.css'    );
        Yii::app()->clientScript->registerScriptFile( $config['themeUri'] . "/bootstrap_302/dist/js/bootstrap.js"            );

        // setting meta tag
        MetaTagManager::init();
        MetaTagManager::setName('robots','nofollow, noindex');
        MetaTagManager::setName('viewport', 'width=device-width, initial-scale=1.0');

    }

    /**
     *  recirect to main page
     */
    public function redirectMainPage()
    {
        $this->redirect( $this->createUrl('/') );
        exit;
    }

}

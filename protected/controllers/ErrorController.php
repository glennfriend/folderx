<?php

class ErrorController extends CController
{

    public function actionIndex()
    {
        switch( APPLICATION_PORTAL )
        {
            case 'admin':
                $this->adminErrorPage();
                break;
            case 'site':
                $this->siteErrorPage();
                break;
            case 'front':
                $this->frontErrorPage();
            case 'wow':
                $this->wowErrorPage();
                break;
            default:
                echo 'Error Page 404';
        }
    }

    /**
     *  admin error page
     */
    public function adminErrorPage()
    {
        $this->render( '/admin/error', Array(
            'error' => Yii::app()->errorHandler->error,  
        ));
    }

    /**
     *  site error page
     */
    public function siteErrorPage()
    {
        $this->render( '/site/error', Array(
            'error' => Yii::app()->errorHandler->error,  
        ));
    }

    /**
     *  frton error page
     */
    public function frontErrorPage()
    {
        $this->render( '/front/error', Array(
            'error' => Yii::app()->errorHandler->error,  
        ));
    }

    public function wowErrorPage()
    {
        $this->render( '/wow/error', Array(
            'error' => Yii::app()->errorHandler->error,  
        ));
    }
}

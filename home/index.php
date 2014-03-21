<?php
header('Content-Type: text/html; charset=utf-8');
define('APPLICATION_PORTAL','home');
require_once(__DIR__.'/../protected/start.php');
$app = Yii::createWebApplication( getApplicationConfig() );
BaseCore::init();
$app->run();

<?php
/**
 *  仿製 CDbLogRoute 
 *
 */
class YiiLogRouteBrg
{

    /**
     * @var boolean whether to enable this log route. Defaults to true.
     */
    public $enabled=true;

    /**
     *
     */
    public function init()
    {
        // [logTableName] => yii_applog
        // [connectionID] => db
        // [levels] => error, warning
    }
    
    /**
     *  Retrieves filtered log messages from logger for further processing.
     *  @param CLogger $logger logger instance
     *  @param boolean $processLogs whether to process the logs after they are collected from the logger
     */
    public function collectLogs( $logger, $processLogs=false )
    {
        $yiiApplogs = new YiiApplogs();
        foreach ( $logger->getLogs() as $log ) {

            $level = & $log[1];
            if ( false === strpos( $this->levels, $level ) ) {
                continue;
            }

            $message  = & $log[0];
            $category = & $log[2];
            $time     = & $log[3];

            $yiiApplog = new YiiApplog();
            $yiiApplog->setLevel( $level );
            $yiiApplog->setCategory( $category );
            $yiiApplog->setLogtime( $time );
            $yiiApplog->setMessage( $message );
            $yiiApplogs->addYiiApplog( $yiiApplog );

        }
    }

}

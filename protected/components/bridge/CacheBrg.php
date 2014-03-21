<?php

/**
 *  bridge Yii Cache
 */
class CacheBrg
{

    /* --------------------------------------------------------------------------------
        access
    -------------------------------------------------------------------------------- */

    /**
     *  get cache
     */
    public static function get( $key )
    {
        return Yii::app()->cache->get( $key );
    }

    // public static function has( $key )


    /* --------------------------------------------------------------------------------
        write
    -------------------------------------------------------------------------------- */

    /**
     *  set cache
     */
    public static function set( $key, $value, $expire=APPLICATION_CACKE_LIFETIME )
    {
        Yii::app()->cache->set( $key, $value, $expire );
    }

    // public static function forever 無時間限制的快取

    /**
     *  remove cache
     */
    public static function remove( $key )
    {
        Yii::app()->cache->delete( $key );
    }

    // public static function removePrefix 移除該值開頭的所有快取

    /**
     *  clean all cache data
     */
    public static function flush()
    {
        Yii::app()->cache->flush();
    }

}


<?php

class SessionBrg
{

    /* --------------------------------------------------------------------------------
        access
    -------------------------------------------------------------------------------- */

    /**
     *  get session
     *
     *  TODO: 可以考慮支持用 "." 的方式取得多維陣列下的值 => get('user.name')
     *
     */
    static public function get( $key, $defaultValue=null )
    {
        $val = Yii::app()->session->get($key);
        if ( !$val && $defaultValue ) {
            return $defaultValue;
        }
        return $val;
    }

    /* --------------------------------------------------------------------------------
        write
    -------------------------------------------------------------------------------- */

    /**
     *
     */
    static public function set( $key, $value )
    {
        return Yii::app()->session->add($key, $value );
    }

    /**
     *
     */
    static public function remove( $key )
    {
        unset(Yii::app()->session[$key]);
    }

    /**
     *
     */
    static public function destroy()
    {
        return Yii::app()->session->destroy();
    }

}

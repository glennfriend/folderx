<?php

class TextValidator
{

    /**
     *  validate normal account
     *
     *  Allow:
     *      a-z A-Z 0-9 _
     *
     *  @param  string $name
     *  @param  int    $minSize 最短的帳號長度
     *  @return boolean
     */
    static public function validateNormalAccount( $account , $minSize=6 )
    {
        if( strlen($account) < $minSize ) {
            return false;
        }
        $regex = '/^[a-zA-Z][a-zA-Z0-9_]+$/s';
        return preg_match( $regex, $account );
    }

    /**
     *  validate normal topic
     *
     *  Allow:
     *      a-z
     *      A-Z
     *      0-9
     *      , . - ' & @ ( ) space
     *
     *  @param  string $topic
     *  @return boolean
     */
    static public function validateNormalTopic( $topic )
    {
        $regex = '/^[a-zA-Z0-9-(,@&)\.\'\ ]+$/s';
        return preg_match( $regex, $topic );
    }

    /**
     *  validate amalica phone
     *
     *  example:
     *
     *      (999) 999-9999
     *
     *  @param  string $phone
     *  @return boolean
     */
    static public function validateAmalicaPhone( $phone )
    {
        $regex = '/^[(][0-9]{3}[)][ ][0-9]{3}-[0-9]{4}$/';
        return preg_match( $regex, $phone );
    }




}


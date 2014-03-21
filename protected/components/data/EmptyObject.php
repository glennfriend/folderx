<?php

exit;

class EmptyObject
{

    function __get( $value )
    {
        // return '[EMPTY]';
        return null;
    }

    function __call( $name, $value )
    {
        // return '[UNKNOWN]';
        return null;
    }
}


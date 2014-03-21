<?php

/**
 *  get a home app config value
 *
 *  @see ccHelper_getappConfigOutput
 */
function ccHelper_getAppConfig( $key )
{
    $config = cc('appConfigOutput','array');
    if ( isset($config[$key]) ) {
        return $config[$key];
    }

    return null;
}

<?php

$configSetting = function( $cf )
{
    // $cf['name'] = '';
    $cf['defaultController'] = 'home';
    $cf['modules'] = array(
        'home',
    );
    $cf['components']['rules'] = array(
        // default
        '<_m:\w+>/<_c:\w+>/<_a:\w+>' => '<_m>/<_c>/<_a>',
                 '<_c:\w+>/<_a:\w+>' =>      '<_c>/<_a>',
    );

    return $cf;
};

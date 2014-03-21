<?php

/**
 *  I18n db-object field value translation
 *
 *  "user family" object example:
 *      cc('attribLgg', $userFamily, 'status', 1 );     // change to dbobject_userfamily_status_open   "啟用"
 *      cc('attribLgg', $userFamily, 'status', 2 );     // change to dbobject_userfamily_status_close  "停用"
 *      cc('attribLgg', $userFamily, 'status', 9 );     // change to dbobject_userfamily_status_delete "刪除"
 *
 *  @param object,  dbobject
 *  @param string,  field name
 *  @param any,     field value
 *  @return string or empty string
 */
function ccHelper_attribLgg( $dbobject, $field, $value  )
{
    $className = strtolower(get_class($dbobject));

    $list = cc('attribList', $dbobject, $field );
    foreach( $list as $field => $fieldValue ) {
        if( $fieldValue == $value ) {
            return lgg( strtolower('dbobject_'. $className .'_'. $field) );
        }
    }

    return '';
}

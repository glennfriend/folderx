<?php

/**
 *
 */
class YiiApplog extends BaseObject
{

    /**
     *  請依照 table 正確填寫該 field 內容
     *  @return array()
     */
    public static function getTableDefinition()
    {
        return array(
            'id' => array(
                'type'    => 'integer',
                'filters' => array('intval'),
                'storage' => 'getId',
                'field'   => 'id',
            ),
            'level' => array(
                'type'    => 'string',
                'filters' => array('strip_tags','trim'),
                'storage' => 'getLevel',
                'field'   => 'level',
            ),
            'category' => array(
                'type'    => 'string',
                'filters' => array('strip_tags','trim'),
                'storage' => 'getCategory',
                'field'   => 'category',
            ),
            'logtime' => array(
                'type'    => 'integer',
                'filters' => array('intval'),
                'storage' => 'getLogtime',
                'field'   => 'logtime',
            ),
            'message' => array(
                'type'    => 'string',
                'filters' => array('strip_tags','trim'),
                'storage' => 'getMessage',
                'field'   => 'message',
            ),
        );
    }

}


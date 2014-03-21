<?php

/**
 *  
 */
class Config extends BaseObject
{
    CONST DISPLAY_ALL   = -1;
    CONST DISPLAY_YES   = 1;
    CONST DISPLAY_NO    = 2;

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
            'key' => array(
                'type'    => 'string',
                'filters' => array('strip_tags','trim'),
                'storage' => 'getKey',
                'field'   => 'key',
            ),
            'value' => array(
                'type'    => 'string',
                'filters' => array('trim'),
                'storage' => 'getValue',
                'field'   => 'value',
            ),
            'name' => array(
                'type'    => 'string',
                'filters' => array('strip_tags','trim'),
                'storage' => 'getName',
                'field'   => 'name',
            ),
            'description' => array(
                'type'    => 'string',
                'filters' => array('strip_tags','trim'),
                'storage' => 'getDescription',
                'field'   => 'description',
            ),
            'display' => array(
                'type'    => 'integer',
                'filters' => array('intval'),
                'storage' => 'getDisplay',
                'field'   => 'display',
                'value'   => self::DISPLAY_YES,
            ),
            'seqNo' => array(
                'type'    => 'integer',
                'filters' => array('intval'),
                'storage' => 'getSeqNo',
                'field'   => 'seq_no',
                'value'   => 100,
            ),
            'group' => array(
                'type'    => 'integer',
                'filters' => array('intval'),
                'storage' => 'getGroup',
                'field'   => 'group',
            ),
            'properties' => array(
                'type'    => 'string',
                'filters' => array('arrayval'),
                'storage' => 'getProperties',
                'field'   => 'properties',
            ),
        );
    }

    /**
     *  validate
     *  @return messages Array()
     */
    public function validate()
    {
        $messages = Array();

        // 不予許空值, 不予許錯誤的格式
        if( !$this->getKey() || !preg_match('/^[0-9a-z_\-]+$/is', $this->getKey() ) ) {
            $messages['key'] = '該欄位必須是英文或數字, 不可以使用特殊符號';
        }

        return $messages;
    }

    /**
     *  filter model data
     */
    public function filter()
    {
    }

    /* ------------------------------------------------------------------------------------------------------------------------
        basic method 
    ------------------------------------------------------------------------------------------------------------------------ */


    /* ------------------------------------------------------------------------------------------------------------------------
        extends
    ------------------------------------------------------------------------------------------------------------------------ */



}


<?php

/**
 *  Item
 *
 */
class Item extends BaseObject
{
    // type
    const TYPE_ALL        = -1;
    const TYPE_FILE       = 1;
    const TYPE_DIRECTORY  = 2;

    /**
     *  請依照 table 正確填寫該 field 內容
     *  @return array()
     */
    public static function getTableDefinition()
    {
        return array(
            'key' => array(
                'type'    => 'string',
                'filters' => array('strip_tags','trim'),
                'storage' => 'getKey',
                'field'   => 'key',
            ),
            'real' => array(
                'type'    => 'string',
                'filters' => array('trim'),
                'storage' => 'getReal',
                'field'   => 'real',
            ),
            'uri' => array(
                'type'    => 'string',
                'filters' => array('trim'),
                'storage' => 'getUri',
                'field'   => 'uri',
            ),
            'name' => array(
                'type'    => 'string',
                'filters' => array('trim'),
                'storage' => 'getName',
                'field'   => 'name',
            ),
            'extension' => array(
                'type'    => 'string',
                'filters' => array(),
                'storage' => 'getExtension',
                'field'   => 'extension',
            ),
            'type' => array(
                'type'    => 'integer',
                'filters' => array('intval'),
                'storage' => 'getType',
                'field'   => 'type',
                'value'   => null,
            ),
            'size' => array(
                'type'    => 'integer', // mediumint
                'filters' => array('intval'),
                'storage' => 'getSize',
                'field'   => 'size',
            ),
            'mtime' => array(
                'type'    => 'timestamp',
                'filters' => array('dateval'),
                'storage' => 'getMtime',
                'field'   => 'mtime',
                'value'   => strtotime('1970-01-01'),
            ),
            'searchKeys' => array(
                'type'    => 'string',
                'filters' => array('strip_tags','trim'),
                'storage' => 'getSearchKeys',
                'field'   => 'search_keys',
            ),
        );
    }

    /**
     *  validate
     *  @return messages array()
     */
    public function validate()
    {
        $messages = array();

        if ( !$this->getPath() ) {
            $messages['real'] = 'The field is required.';
        }

        if ( !$this->getName() ) {
            $messages['name'] = 'The field is required.';
        }

        // choose value
        $result = false;
        foreach ( cc('attribList', $this, 'type') as $name => $value ) {
            if ( $this->getType() === $value ) {
                $result = true;
                break;
            }
        }
        if (!$result) {
            $messages['type'] = 'type incorrect';
        }

        return $messages;
    }

    /* ------------------------------------------------------------------------------------------------------------------------
        basic method rewrite or extends
    ------------------------------------------------------------------------------------------------------------------------ */

    /**
     *  Disabled methods
     *  @return array()
     */
    public static function getDisabledMethods()
    {
        return array();
    }


    /* ------------------------------------------------------------------------------------------------------------------------
        extends
    ------------------------------------------------------------------------------------------------------------------------ */

    /**
     *  取得 網路芳鄰 的連結
     */
    public function getSambaUrl()
    {
        return UrlManager::baseResourceUrl() . $this->getUri() . '/' . $this->getFullName();
    }

    /**
     *  get show resource link
     */
    public function getResourceUrl()
    {
        return url(
            '/home/resource', array('key'=>$this->getKey())
        );
    }

    /**
     *  設定 Item 可搜尋的內容, 這些將會寫入資料表中供搜尋使用
     */
    public function resetSearchKeys( $baseUrl )
    {
        $file     = $this->getReal();
        $fileName = $this->getName();

        $path = substr( $file, strlen($baseUrl) );
        $path = dirname( $path );

        $result = explode('/', $path);
        $result[] = $fileName;
        
        $result = join(',', array_unique($result) );
        $result = ltrim($result, ', ');
        if ( $result ) {
            $result .= ',';
        }
        $this->store['search_keys'] = $result;
    }

    /**
     *  取得有英文單位的 size
     *  Converts filesize in Kb, Mb, Gb, Tb or Zb
     */
    public function getFileSize( $dec = 2 )
    {
        $type = Array( 'bytes', 'KB', 'MB', 'GB', 'TB', 'ZB' );
        $size = $this->getSize();
        $times = 0; // 要除以 1024 多少次

        $tmp = $size;
        while( $tmp > 1024 ) {
            $tmp = $tmp / 1024;
            $times++;
        }

        if( $times === 0 ) {
            $display = $size .' '. $type[$times];
        }
        else {
            $size = $size / pow( 1024, $times );
            $display = number_format( $size, $dec, '.', '' ) .' '. $type[$times];
        }
        return $display;
    }

    /**
     *  取得檔案完整名稱
     */
    public function getFullName()
    {
        if ( $extension = $this->getExtension() ) {
            return $this->getName() .'.'. $extension;
        }
        return $this->getName();
    }

    /* ------------------------------------------------------------------------------------------------------------------------
        lazy loading methods
    ------------------------------------------------------------------------------------------------------------------------ */


}

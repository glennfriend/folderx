<?php

// 停用 yii recrd, 改用 zend db
exit;

class BaseModel
{

    /**
     *  任何錯誤的情況, 有可能會儲存狀態在這裡
     */
    protected $_error = null;

    /**
     * get model name
     * @return string
     */
    public function getModelName()
    {
        return get_class($this);
    }

    /**
     *  table master field key
     *  @return string
     */
    public function getPk()
    {
        return 'id';
    }

    /**
     * you can rewrite it
     * @return string
     */
    public function getFullCacheKey( $value, $key )
    {
        return "CACHE_MODELS.". trim($key) .".". trim($value);
    }

    /*
    public function getTableDefinition()
    {
        // rewrite it
        return array();
    }
    */

    /**
     *  get the record model
     *  取得的 Record 不要做 cache
     *  讓程式每次都取得新的 Record
     *  詳見 YII Record 的設計機制
     *
     *  return record
     */
    protected function _getRecord()
    {
        $modelName = $this->getModelName();
        $parentModelName = $modelName.'Record';
        return new $parentModelName();
    }

    /**
     *  任何傳回錯誤的狀態, 可以檢查是否有 model error 物件訊息
     *  @return CDbException object
     */
    public function getError()
    {
        return $this->_error;
    }

    /* ================================================================================
        write database
    ================================================================================ */

    /**
     *  資料從 object 寫入到 database 之前要做資料轉換的動作
     */
    protected function _objectToRecord( $object )
    {
        $record = self::_getRecord();
        foreach ( $object->getTableDefinition() as $key => $item ) {

            $type   = $item['type'];
            $field  = $item['field'];
            $method = $item['storage'];
            $value  = $object->$method();

            if( is_object($value) || is_array($value) ) {
                $value = serialize($value);
            }

            switch ( $type ) {
                case 'timestamp':
                    $value = date('Y-m-d H:i:s', $value);
                    break;
            }

            $record->$field = $value;
        }
        return $record;
    }

    /**
     * add object to database
     * @param object  - dbobject
     * @param boolean - false is default boolean, true is return insert id
     * @return  boolean or int
     */
    protected function _addObject( $object, $isReturnInsertId=false )
    {
        $record = $this->_objectToRecord( $object );

        // insert error
        // 例如: 重覆的鍵值 引發了 衝突
        $this->_error = null;
        $result = false;
        try {
            $result = $record->insert();
        }
        catch( Exception $e ) {
            $this->_error = $e;
        }

        if( !$result ) {
            return false;
        }

        if( $isReturnInsertId ) {
            return $record->getCommandBuilder()->getLastInsertID(  $record->getMetaData()->tableSchema  );
        }
        return $result;
    }

    /**
     * update object to database
     * Yii "Active Record" update 當資料完全相同時, 並不會更新, 所以傳回值會是 0
     *
     * @param object
     * @return int
     */
    protected function _updateObject( $object )
    {
        $record = $this->_objectToRecord( $object );

        // update error
        // 例如: 重覆的鍵值 引發了 衝突
        $this->_error = null;
        $result = false;
        try {
            $pk = self::getPk();
            $result = $record->updateByPk( $record->$pk, $record );
        }
        catch( Exception $e ) {
            $this->_error = $e;
        }

        return $result;
    }

    /**
     * delete object to database
     * @param key
     * @return int
     */
    protected function _deleteObject( $key )
    {
        $record = self::_getRecord();
        return $record->deleteByPk( $key );
    }

    /* ================================================================================
        access database
    ================================================================================ */

    /**
     * get object and cache
     * @param string - field name
     * @param string - field value
     * @param string - cache key
     * @return object or false
     */
    protected function _getObject( $field, $value, $cacheKey )
    {
        $fullCacheKey = self::getFullCacheKey( $value, $cacheKey );
        $object = CacheBrg::get( $fullCacheKey );
        if( $object ) {
            return $object;
        }

        $record = self::_getRecord();
        $record = $record->findByAttributes(array(
            $field => $value,
        ));
        if( !$record ) {
            return false;
        }

        $object = $this->recordToObject( $record );
        CacheBrg::set( $fullCacheKey, $object );
        return $object;
    }

    /**
     * find all by method -> $model->getObject($id)
     * @param $method       - method name, example: "getUser", "getBlog", "getArticle", "getComment"
     * @param $condition    - sql condition
     * @param $page         - page start from 1 , get all use -1
     * @param $itemsPerPage
     * @return objects
     */
    protected function _findObjectsByMethod( $getMethod, $condition=Array() , $page=1, $itemsPerPage=APPLICATION_ITEMS_PER_PAGE )
    {
        if( -1 == $page ) {
            unset(
                $condition['limit'],
                $condition['offset']
            );
        }
        else {
            $page = (int) $page;
            if( $page == 0 ) {
                $page = 1;
            }
            $condition += Array(
                'limit'  => $itemsPerPage,
                'offset' => ($page-1)*$itemsPerPage,
            );
        }

        // get id only
        $condition['select'] = Array('id');

        $record = self::_getRecord();
        $records = $record->findAll($condition);
        $objects = Array();
        foreach( $records as $record ) {
            $objects[] = $this->$getMethod( $record->id );
        }

        return $objects;
    }

    /**
     * get row count
     * @param $condition - sql condition
     * @return int
     */
    protected function _getNumObjects( $condition='' )
    {
        $record = self::_getRecord();
        $num = $record->count($condition);
        return $num;
    }

}

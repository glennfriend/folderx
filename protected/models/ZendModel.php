<?php

/*
    Zend Db sample:
        https://gist.github.com/ralphschindler/3949548
        http://www.maltblue.com/tutorial/zend-db-sql-the-basics
        http://www.maltblue.com/tutorial/zend-db-sql-select-easy-where-clauses
        http://framework.zend.com/manual/2.0/en/modules/zend.db.sql.html
*/
class ZendModel
{

    /**
     *  任何錯誤的情況, 要將狀態儲存在此
     */
    protected $error = null;

    /**
     *  table name
     */
    protected $tableName = null;

    /**
     *  get method
     */
    protected $getMethod = null;

    /**
     *  table master field key
     */
    protected $pk = 'id';

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
     *  任何傳回錯誤的狀態, 可以檢查是否有 model error 物件訊息
     *  @return error info array
     */
    public function getError()
    {
        if ( !$this->error ) {
            return array(
                'is_error' => false,
                'message'  => '',
            );
        }
        return array(
            'is_error'  => true,
            'message'   => $this->error->getMessage()
        );
    }

    static protected $adapter = null;

    protected function getAdapter()
    {
        if ( self::$adapter ) {
            return self::$adapter;
        }
                
        self::$adapter = new Zend\Db\Adapter\Adapter(array(
            'driver'    => 'Pdo_Mysql',
            'dsn'       => 'mysql:host='. APPLICATION_DB_MYSQL_HOST .';dbname='. APPLICATION_DB_MYSQL_DB,
         // 'database'  => APPLICATION_DB_MYSQL_DB,
            'username'  => APPLICATION_DB_MYSQL_USER,
            'password'  => APPLICATION_DB_MYSQL_PASS,
            'driver_options' => array(
                PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES \'UTF8\''
            )
        ));

        return self::$adapter;
    }

    /**
     *  Zend db query, access
     *
     *  @param Zend\Db\Sql\Select
     *  @return statement result object
     */
    protected function query( $select )
    {
        $adapter = $this->getAdapter();
        $zendSql = new Zend\Db\Sql\Sql($adapter);

        // developer tool
        MonitorManager::sqlQuery( $select->getSqlString( $adapter->getPlatform() ) );

        $this->error = null;
        try {
            $statement = $zendSql->prepareStatementForSqlObject($select);
            $results = $statement->execute();
        }
        catch( Exception $e ) {
            $this->error = $e;
            return false;
        }
        return $results;
    }

    /**
     *  Zend db execute, write
     *
     *  @param zend sql object
     *      Zend\Db\Sql\Insert
     *      Zend\Db\Sql\Update
     *      Zend\Db\Sql\Delete
     *  @return statement result object
     */
    protected function execute( $write )
    {
        $adapter = $this->getAdapter();
        $sql = $write->getSqlString( $adapter->getPlatform() );

        // developer tool
        MonitorManager::executeQuery( $write->getSqlString( $adapter->getPlatform() ) );

        $this->error = null;
        try {
            $statement = $adapter->query( $sql );
            $result = $statement->execute();
        }
        catch( Exception $e ) {
            // insert/update/delete error
            // 例如: 重覆的鍵值 引發了 衝突
            $this->error = $e;
            return false;
        }
        return $result;
    }

    /* ================================================================================
        write database
    ================================================================================ */

    /**
     * add object to database
     * @param object  - dbobject
     * @param boolean - false is default boolean, true is return insert id
     * @return  boolean or int
     */
    protected function addObject( $object, $isReturnInsertId=false )
    {
        $row = $this->objectToArray( $object );

        $insert = new Zend\Db\Sql\Insert( $this->tableName );
        $insert->values($row);
        $result = $this->execute($insert);
        if( !$result ) {
            return false;
        }

        if( $isReturnInsertId ) {
            return $result->getGeneratedValue();
        }
        return true;
    }

    /**
     *  update object to database
     *  更新時, 若資料完全相同, 不會有更新的動作, 所以傳回值會是 0
     *
     * @param object
     * @return int, affected row count
     */
    protected function updateObject( $object )
    {
        $row = $this->objectToArray( $object );
        $pk = $this->pk;
        $pkValue = $row[$pk];
        unset($row[$pk]);

        $update = new Zend\Db\Sql\Update( $this->tableName );
        $update->where(array( $pk => $pkValue ));
        $update->set($row);

        $result = $this->execute($update);
        if( !$result ) {
            return false;
        }
        return $result->count();
    }

    /**
     * delete object to database
     * @param key
     * @return int, affected row count
     */
    protected function deleteObject( $key )
    {
        $delete = new Zend\Db\Sql\Delete( $this->tableName );
        $delete->where(array( $this->pk => $key));

        $result = $this->execute($delete);
        if( !$result ) {
            return false;
        }
        return $result->count();
    }

    /**
     *  資料從 object 寫入到 database 之前要做資料轉換的動作
     */
    protected function objectToArray( $object )
    {
        $data = array();
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

            $data[$field] = $value;
        }
        return $data;
    }

    /* ================================================================================
        access database
    ================================================================================ */

    /**
     *  get ZF2 Zend Db Select
     *  @return Zend\Db\Sql\Select
     */
    protected function getDbSelect( $isSetDefaultValue=true )
    {
        $select = new Zend\Db\Sql\Select();
        if ( $isSetDefaultValue ) {
            $select->columns(array($this->pk));
            $select->from( $this->tableName );
        }
        return $select;
    }

    /**
     * get object and cache
     * @param string - field name
     * @param string - field value
     * @param string - cache key
     * @return object or false
     */
    protected function getObject( $field, $value, $cacheKey=null )
    {
        if ( $cacheKey ) {
            $fullCacheKey = self::getFullCacheKey( $value, $cacheKey );
            $object = CacheBrg::get( $fullCacheKey );
            if( $object ) {
                return $object;
            }
        }

        $select = $this->getDbSelect();
        $select->columns(array('*'));
        $select->where(array( $field => $value ));

        $result = $this->query($select);
        if( !$result ) {
            return false;
        }

        $row = $result->current();
        if( !$row ) {
            return false;
        }

        $object = $this->mapRow( $row );
        if ( $cacheKey ) {
            CacheBrg::set( $fullCacheKey, $object );
        }
        return $object;
    }

    /**
     *  find option
     *
     *  @param $select   - Zend\Db\Sql\Select
     *  @param $option   - option array
     *  @return objects or empty array
     */
    protected function findObjects( $select, $option=array() )
    {
        $orderBy      = isset($option['_order'])        ? $option['_order']        : '' ;
        $page         = isset($option['_page'])         ? $option['_page']         : 1  ;
        $itemsPerPage = isset($option['_itemsPerPage']) ? $option['_itemsPerPage'] : APPLICATION_ITEMS_PER_PAGE;

        if ( $orderBy ) {
            $select->order( trim($orderBy) );
        }
        if( -1 !== $page ) {
            $page = (int) $page;
            if( $page == 0 ) {
                $page = 1;
            }
            $select->limit( $itemsPerPage );
            $select->offset( ($page-1)*$itemsPerPage );
        }
        $result = $this->query($select);
        if ( !$result ) {
            return array();
        }


        $objects = array();
        $getMethod = $this->getMethod;
        while( $row = $result->next() ) {
            $objects[] = $this->$getMethod( $row[$this->pk] );
        };
        return $objects;
    }

    /**
     * get row count
     * @param $condition - sql condition
     * @return int
     */
    protected function numFindObjects( $select='' )
    {
        $expression = array('total' => new \Zend\Db\Sql\Expression('count(*)'));
        $select->columns( $expression );

        $result = $this->query($select);
        if( !$result ) {
            return 0;
        }

        $row = $result->current();
        return $row['total'];
    }

}

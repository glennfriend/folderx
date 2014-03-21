<?php

/**
 *
 */
class Items extends ZendModel
{
    const CACHE_ITEM = 'cache_item';

    /**
     *  PRIMARY KEY
     */
    protected $pk = 'key';

    /**
     *  table name
     */
    protected $tableName = 'items';

    /**
     *  get method
     */
    protected $getMethod = 'getItem';

    /**
     *  get db object by record
     *  @param  row
     *  @return TahScan object
     */
    public function mapRow( $row )
    {
        $object = new Item();
        $object->setKey         ( $row['key']               );
        $object->setReal        ( $row['real']              );
        $object->setUri         ( $row['uri']               );
        $object->setName        ( $row['name']              );
        $object->setExtension   ( $row['extension']         );
        $object->setType        ( $row['type']              );
        $object->setSize        ( $row['size']              );
        $object->setMtime       ( strtotime($row['mtime'])  );
        $object->setSearchKeys  ( $row['search_keys']       );
        return $object;
    }

    /* ================================================================================
        write database
    ================================================================================ */

    /**
     *  add Item
     *  @param Item object
     *  @return insert key or false
     */
    public function addItem( $object )
    {
        $result = $this->addObject( $object );
        if ( !$result ) {
            return false;
        }

        $this->preChangeHook( $object );
        return $result;
    }

    /**
     *  update Item
     *  @param Item object
     *  @return int
     */
    public function updateItem( $object )
    {
        $result = $this->updateObject( $object );
        if ( !$result ) {
            return false;
        }

        $this->preChangeHook( $object );
        return $result;
    }

    /**
     *  delete Item
     *  @param string key
     *  @return boolean
     */
    public function deleteItem( $key )
    {
        $object = $this->getItem($key);
        if ( !$object || !$this->deleteObject($key) ) {
            return false;
        }

        $this->preChangeHook( $object );
        return true;
    }

    /**
     *  pre change hook, first remove cache, second do something more
     *  about add, update, delete
     *  @param object
     */
    public function preChangeHook( $object )
    {
        // first, remove cache
        $this->removeCache( $object );
    }

    /**
     *  remove cache
     *  @param object
     */
    protected function removeCache( $object )
    {
        if ( !$object->getKey() ) {
            return;
        }

        $cacheKey = $this->getFullCacheKey( $object->getKey(), Items::CACHE_ITEM );
        CacheBrg::remove( $cacheKey );
    }

    /* ================================================================================
        read access database
    ================================================================================ */

    /**
     *  get Item by key
     *  @param  string key
     *  @return object or false
     */
    public function getItem( $key )
    {
        return $this->getObject( 'key', $key, Items::CACHE_ITEM  );
    }

    /* ================================================================================
        find Items and get count
        多欄、針對性的搜尋, 主要在後台方便使用, 使用 and 搜尋方式
    ================================================================================ */

    /**
     *  find many Item
     *  @param  option array
     *  @return objects or empty array
     */
    public function findItems( $opt=array() )
    {
        $opt += array(
            'key'           => '',
            'type'          => Item::TYPE_ALL,
            '_order'        => 'mtime DESC',
            '_page'         => 1,
            '_itemsPerPage' => APPLICATION_ITEMS_PER_PAGE
        );
        return $this->findItemsReal( $opt );
    }

    /**
     *  get count by "findItems" method
     *  @return int
     */
    public function numFindItems( $opt=array() )
    {
        $opt += array(
            'key'           => '',
            'type'          => Item::TYPE_ALL,
        );
        return $this->findItemsReal( $opt, true );
    }

    /**
     *  findItems option
     *  @return objects or record total
     */
    protected function findItemsReal( $opt=array(), $isGetCount=false )
    {
        $select = $this->getDbSelect();

        if ( '' !== $opt['key'] ) {
            $select->where->and->equalTo('key', $opt['key']);
        }
        if ( Item::TYPE_ALL !== $opt['type'] ) {
            $select->where(array('type' => $opt['type']));
        }

        if ( !$isGetCount ) {
            return $this->findObjects( $select, $opt );
        }
        return $this->numFindObjects( $select );
    }


    /* ================================================================================
        search Items and get count
        集中於一個欄位的搜尋, 主要於前台使用, 使用 or 搜尋方式
    ================================================================================ */

    /**
     *  search many Item
     *  @param  option array
     *  @return objects or empty array
     */
    public function searchItems( $opt=array() )
    {
        $opt += array(
            '_searchKey'    => '',
            '_order'        => 'mtime DESC',
            '_page'         => 1,
            '_itemsPerPage' => APPLICATION_ITEMS_PER_PAGE
        );
        return $this->searchItemsReal( $opt );
    }

    /**
     *  get count by "searchItems" method
     *  @return int
     */
    public function numSearchItems( $opt=array() )
    {
        $opt += array(
            '_searchKey' => ''
        );
        return $this->searchItemsReal( $opt, true );
    }

    /**
     *  searchItems option
     *  @return objects or record total
     */
    protected function searchItemsReal( $opt=array(), $isGetCount=false )
    {
        $select = $this->getDbSelect();

        if ( '' !== $opt['_searchKey'] ) {
            $value = '%'. $opt['_searchKey'] .'%';
            $select->where->nest
                ->or->like(     'name',        '%'. $opt['_searchKey'] .'%'  )
                ->and->like(    'search_keys', '%'. $opt['_searchKey'] .'%,' );
        }

        if ( !$isGetCount ) {
            return $this->findObjects( $select, $opt );
        }
        return $this->numFindObjects( $select );
    }


    /* ================================================================================
        extends
    ================================================================================ */

}

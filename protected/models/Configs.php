<?php

/**
 *
 */
class Configs extends ZendModel
{

    const CACHE_CONFIG      = 'cache_config';
    const CACHE_CONFIG_ALL  = 'cache_config_all';

    /**
     *  table name
     */
    protected $tableName = 'configs';

    /**
     *  get method
     */
    protected $getMethod = 'getConfig';


    /**
     *  get db object by record
     *  @param  row
     *  @return TahScan object
     */
    public function mapRow( $row )
    {
        $object = new Config();
        $object->setId              ( $row['id']                        );
        $object->setKey             ( $row['key']                       );
        $object->setValue           ( $row['value']                     );
        $object->setName            ( $row['name']                      );
        $object->setDescription     ( $row['description']               );
        $object->setDisplay         ( $row['display']                   );
        $object->setSeqNo           ( $row['seq_no']                    );
        $object->setGroup           ( $row['group']                     );
        $object->setProperties      ( unserialize($row['properties'])   );
        return $object;
    }

    /* ================================================================================
        write database
    ================================================================================ */

    /**
     *  update Config
     *  @param Config object
     *  @return int
     */
    public function updateConfig( $object )
    {
        $result = $this->updateObject( $object );
        if( !$result ) {
            return false;
        }
        $this->preChangeHook( $object );
        return $result;
    }

    /**
     *  pre change hook, remove cache, do something more
     *  about add, update, delete
     *  @param object
     */
    public function preChangeHook( $object )
    {
        $this->removeCache( $object );
    }

    /**
     *  remove cache
     *  @param object
     */
    protected function removeCache( $object )
    {
        if( $object->getId() >= 1 ) {
            $cacheKey = $this->getFullCacheKey( $object->getId(), Configs::CACHE_CONFIG );
            CacheBrg::remove( $cacheKey );
        }

        $allCacheKey = $this->getFullCacheKey( '_ALL', Configs::CACHE_CONFIG_ALL );
        CacheBrg::remove( $allCacheKey );

    }


    /* ================================================================================
        access database
    ================================================================================ */

    /**
     *  get Config by id
     *  @param  int id
     *  @return object or empty array
     */
    public function getConfig( $id )
    {
        return $this->getObject( 'id', $id, Configs::CACHE_CONFIG );
    }

    /**
     *  find all Config
     *  @param
     *  @return objects or empty array
     */
    public function findAllConfigs()
    {
        $fullCacheKey = self::getFullCacheKey( '_ALL', Configs::CACHE_CONFIG_ALL );
        $objects = CacheBrg::get( $fullCacheKey );
        if( $objects ) {
            return $objects;
        }

        $select = $this->getDbSelect();
        $opt = array(
            '_order' => 'seq_no ASC, id ASC',
        );
        $objects = $this->findObjects( $select, $opt );


        CacheBrg::set( $fullCacheKey, $objects );
        return $objects;
    }

    /**
     *  get config by key
     *  @param  string $key
     *  @return object or empty array
     */
    public function getConfigByKey( $key )
    {
        $configs = $this->findAllConfigs();
        if( !isset($configs[$key]) ) {
            return Array();
        }
        return $configs[$key];
    }

}


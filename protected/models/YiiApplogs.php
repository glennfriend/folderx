<?php

/**
 *
 */
class YiiApplogs extends ZendModel
{

    /**
     *  table name
     */
    protected $tableName = 'yii_applog';

    /**
     *  get method
     */
    protected $getMethod = 'getYiiApplog';


    /**
     *  covert db row to object
     *  return object
     */
    public function mapRow( $row )
    {
        $object = new YiiApplog();
        $object->setId       ( $row['id']       );
        $object->setLevel    ( $row['level']    );
        $object->setCategory ( $row['category'] );
        $object->setLogtime  ( $row['logtime']  );
        $object->setMessage  ( $row['message']  );
        return $object;
    }

    /* ================================================================================
        write database
    ================================================================================ */

    /**
     *  add YiiApplog
     *  @param object
     *  @return boolean
     */
    public function addYiiApplog( $object )
    {
        $result = $this->addObject( $object );
        if( !$result ) {
            return false;
        }
        return $result;
    }

    /**
     *  delete YiiApplog
     *  @param int id
     *  @return boolean
     */
    public function deleteYiiApplog( $id )
    {
        $object = $this->getYiiApplog($id);
        if( !$object || !$this->deleteObject($id) ) {
            return false;
        }
        return true;
    }

    /* ================================================================================
        access database
    ================================================================================ */

    /**
     *  get YiiApplog by id
     *  @param  int id
     *  @return object or false
     */
    public function getYiiApplog( $id, $level='', $category='' )
    {
        $object = $this->getObject( 'id', $id );
        if ( !$object ) {
            return false;
        }

        if( '' !== $level && $object->getLevel() !== $level ) {
            return false;
        }
        if( '' !== $category && $object->getCategory() !== $category ) {
            return false;
        }

        return $object;
    }

    /**
     *  find many YiiApplog
     *  @param
     *  @return objects or empty array
     */
    public function findYiiApplogs( $opt=array() )
    {
        $opt += array(
            'level'         => '',
            'category'      => '',
            '_searchKey'    => '',
            '_order'        => 'id DESC',
            '_page'         => 1,
            '_itemsPerPage' => APPLICATION_ITEMS_PER_PAGE
        );

        $objects = $this->getByFindYiiApplogsOption( $opt );
        return $objects;
    }

    /**
     *  get count by "findYiiApplogs" method
     *  @return int
     */
    public function getNumYiiApplogs( $opt=array() )
    {
        $opt += array(
            'level'        => '',
            'category'     => '',
            '_searchKey'   => ''
        );

        return $this->getByFindYiiApplogsOption( $opt, true );
    }

    /**
     *  getCommandByFindYiiApplogsOption
     *
     *  "findYiiApplog" and "getNumYiiApplogs" SQL query
     *
     *  @return YII command object
     */
    protected function getByFindYiiApplogsOption( $opt=array(), $isGetCount=false )
    {
        $select = $this->getDbSelect();

        if( '' !== $opt['level'] ) {
            $select->where(array('level', $opt['level'] ));
        }
        if( '' !== $opt['category'] ) {
            $select->where(array('category', $opt['category'] ));
        }
        if( '' !== $opt['_searchKey'] ) {
            $value = '%'. $opt['_searchKey'] .'%';
            $select->where->nest
                ->or->like('level',    $value )
                ->or->like('category', $value )
                ->or->like('message',  $value )
            ;
        }

        if ( !$isGetCount ) {
            return $this->findObjects( $select, $opt );
        }
        else {
            return $this->numFindObjects( $select );
        }

    }

}


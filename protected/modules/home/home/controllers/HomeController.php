<?php

class HomeController extends HomeBaseController
{

    public function actionIndex( $page=1 )
    {
        $myItems = array();

        $searchKey = InputBrg::get('searchKey');
        if ( $searchKey ) {
            $options = array(
                '_searchKey'    => $searchKey,
                '_page'         => $page,
                '_itemsPerPage' => 20,
                '_order'        => 'mtime desc',
            );
            
            //MonitorManager::on();
            $items    = new Items();
            $myItems  = $items->searchItems( $options );
            $rowCount = $items->numSearchItems( $options );
            //MonitorManager::off();

            $this->pageLimit = new PageLimit();
            $this->pageLimit->setBaseUrl( '/' );
            $this->pageLimit->setRowCount( $rowCount );
            $this->pageLimit->setPage( $page );
            $this->pageLimit->setParams(array(
                'searchKey'  => $searchKey,
            ));
        }

        $this->render( 'index', array(
            'items' => $myItems,
        ));
    }


}

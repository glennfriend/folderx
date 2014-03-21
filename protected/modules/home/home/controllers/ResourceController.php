<?php

class ResourceController extends HomeBaseController
{

    public function actionIndex( )
    {
        $key = InputFilterBrg::getText('key');
        if ( !$key ) {
            $this->redirectMainPage();
        }

        $items = new Items();
        $item = $items->getItem( $key );
        if ( !$item ) {
            $this->redirectMainPage();
        }

        $this->render( 'index', array(
            'item' => $item,
        ));
    }


}

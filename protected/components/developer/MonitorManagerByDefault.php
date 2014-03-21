<?php

class MonitorManagerByDefault
{

    /**
     *  
     */
    public function sqlQuery( $sql )
    {
        // return;
        echo '<pre>';
        echo SqlFormatter::format( $sql, false );
        echo "</pre>\n";
    }

    /**
     *  
     */
    public function executeQuery( $sql )
    {
        // return;
        echo '<pre>';
        echo SqlFormatter::format( $sql, false );
        echo "</pre>\n";
    }

}



<?php

namespace SonoranAccounting\Controllers;

/**
 * Base Controller on anchor.sonoranaccounting.com
 * 
 * Author: Kevin Sawicke <kevin@sonoranaccounting.com
 * Copyright: 2017 Sonoran Accounting Services LLC
 */
class BaseController {

    protected $container;

    public function __construct( $container )
    {
        $this->container = $container;
    }
    
    public function endSession()
    {
        unset( $_SESSION['errors'] );
        unset( $_SESSION['sonoranAccountingSession'] );
        
        header("Location: ../auth/logout");
        exit();
    }

}
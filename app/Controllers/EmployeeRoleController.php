<?php

namespace SonoranAccounting\Controllers;

class EmployeeRoleController extends BaseController {

    protected $container;

    public function __construct( $container )
    {
        parent::__construct( $container );
        
        if( !in_array( $_SESSION['sonoranAccountingSession']['role'], [ 'Admin', 'Employee' ] ) ) {
            $this->endSession();
        }
    }

}
<?php

namespace SonoranAccounting\Models;

class BaseModel {
    
    public function __construct()
    {
        require_once '../vendor/rb/rb.php';
        
        if( !\R::testConnection() ) {
            switch( $_SERVER['SERVER_NAME'] ) {
                case 'anchor.sonoranaccounting.com':
                    \R::setup( 'mysql:host=localhost;dbname=sonoran_office',
            'sonoran_office', 'SOMEPASSWORDHERE' );
                    break;
                
                default:
                    \R::setup( 'mysql:host=localhost;dbname=scotchbox',
            'root', 'root' );
                    break;
            }
            
            \R::setAutoResolve( TRUE );
        }
    }
    
}


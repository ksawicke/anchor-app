<?php

namespace SonoranAccounting\Validation;

use Psr\Http\Message\ServerRequestInterface as Request;
use Respect\Validation\Validator as Respect;
use Respect\Validation\Exceptions\NestedValidationException;

/**
 * Handles setting errors to be used in views on anchor.sonoranaccounting.com
 * 
 * Author: Kevin Sawicke <kevin@sonoranaccounting.com
 * Copyright: 2017 Sonoran Accounting Services LLC
 */
class Validator {
    
    private function formatFieldName( $field )
    {
        return ucwords( str_replace( '_', ' ', strtolower( $field ) ) );
    }
    
    public function validate( Request $request, array $rules )
    {
        $this->errors = [];
        foreach( $rules as $field => $rule ) {
            try {
                $rule->setName( $this->formatFieldName( $field ) )->assert( $request->getParam( $field ) );
            } catch ( NestedValidationException $ex ) {
                $this->errors[ $field ] = $ex->getMessages();
            }
        }
        
        $_SESSION['errors'] = ( property_exists( $this, 'errors' ) ? $this->errors : [] );
        
        return $this;
    }
    
    public function failed()
    {
        return !empty( $this->errors );
    }
    
}


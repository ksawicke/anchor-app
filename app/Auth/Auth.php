<?php

namespace SonoranAccounting\Auth;

use SonoranAccounting\Models\User;

/**
 * Handles authorization functionality on anchor.sonoranaccounting.com
 * 
 * Author: Kevin Sawicke <kevin@sonoranaccounting.com>
 * Copyright: 2017 Sonoran Accounting Services LLC
 */
class Auth {
    
    public function attempt( $username, $password )
    {
        // get user by username
        // if !user, return false
        // verify password for that user
        // set into session
        $User = new User;
        $foundUser = $User->getRowByUsername( $username );
        
        if( empty( $foundUser ) ) {
            unset( $_SESSION['errors']['username'] );
            $_SESSION['errors']['username'][] = 'Invalid username/password';
            return false;
        }
        
        if( password_verify( $password, $foundUser['password'] ) ) {
            $_SESSION['sonoranAccountingSession']['username'] = $foundUser['username'];
            $_SESSION['sonoranAccountingSession']['role'] = $foundUser['role'];
            $_SESSION['sonoranAccountingSession']['employee_id'] = $foundUser['employee_id'];
            return true;
        } else {
            unset( $_SESSION['errors']['username'] );
            $_SESSION['errors']['username'][] = 'Invalid username/password';
            return false;
        }
        
        return false;
    }
    
}
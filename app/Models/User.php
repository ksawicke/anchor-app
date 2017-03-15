<?php

namespace SonoranAccounting\Models;

class User extends BaseModel {
    
    /**
     * Gets a user row.
     * 
     * @param type $id
     * @return type
     */
    public function getRowById( $id )
    {
        return \R::getRow( 'SELECT username, email_address, role FROM user WHERE employee_id = ? LIMIT 1', [ $id ] );
    }
    
    public function getRowByUsername( $username )
    {
        return \R::getRow( 'SELECT username, password, role, employee_id FROM user WHERE username = ? LIMIT 1', [ $username ] );
    }
    
    /**
     * Saves a user record.
     * 
     * @param type $allPostPutVars
     * @return type
     */
    public function save( $allPostPutVars )
    {
        $method = ( empty( $allPostPutVars['id'] ) ? 'add' : 'edit' );
        
        $username = strtolower( (string) $allPostPutVars['username'] );
        $email_address = strtolower( (string) $allPostPutVars['email_address'] );

        if( $method=='edit' ) {
            $user_row = \R::getRow( 'SELECT id FROM user WHERE employee_id = ? LIMIT 1', [ $allPostPutVars['employee_id'] ] );
            $user_id = $user_row['id'];
            $user = \R::load( 'user', $user_id );
        } else {
            $user = \R::dispense( 'user' );
        }

        $user->employee_id = $allPostPutVars['employee_id'];
        $user->username = (string) $username;
        
        if( !empty( $allPostPutVars['password'] ) && !empty( $allPostPutVars['confirm_password'] ) &&
            ( $allPostPutVars['password'] == $allPostPutVars['confirm_password'] ) ) {
            $user->password = password_hash( $allPostPutVars['password'], PASSWORD_DEFAULT );
        }
        
        $user->email_address = $email_address;
        $user->role = $allPostPutVars['role'];
        if( $method=='add' ) {
            $user->created = date('Y-m-d h:i:s');
            $user->created_by = $allPostPutVars['modified_by'];
        } else {
            $user->edited = date('Y-m-d h:i:s');
            $user->edited_by = $allPostPutVars['modified_by'];
        }

        $user_id = \R::store( $user );
        
        return $user_id;
    }
    
    /**
     * Saves a new password for a user.
     * 
     * @param type $allPostPutVars
     */
    public function establishPassword( $allPostPutVars )
    {
        $user_row = \R::getRow( 'SELECT id FROM user WHERE employee_id = ? LIMIT 1', [ $allPostPutVars['employee_id'] ] );
        $user_id = $user_row['id'];
        $user = \R::load( 'user', $user_id );
        $user->password = password_hash( 'test123', PASSWORD_DEFAULT );
        \R::store( $user );
    }
    
}


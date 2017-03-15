<?php

namespace SonoranAccounting\Models;

class Client extends BaseModel {
    
    /**
     * Finds all Clients.
     * 
     * @return type
     */
    public function findAll() {
        return \R::findAll( 'client', ' ORDER BY name ASC ' );
    }
    
    /**
     * Finds a Client by ID.
     * 
     * @param type $id
     * @return type
     */
    public function findById( $id )
    {
        return \R::findOne( 'client', ' id = ? ', [ $id ] );
    }
    
    /**
     * Saves a Client.
     * 
     * @param type $allPostPutVars
     * @return type
     */
    public function save( $allPostPutVars )
    {
        $method = ( empty( $allPostPutVars['id'] ) ? 'add' : 'edit' );

        $amount = ( empty( $allPostPutVars['amount'] ) ? 0 : $allPostPutVars['amount'] );

        $client = ( ( $method=='add' ) ? \R::dispense( 'client' ) : \R::load( 'client', $allPostPutVars['id'] ) );
        $client->name = (string) $allPostPutVars['name'];
        $client->number = (string) $allPostPutVars['number'];
        $client->pay_plan = (string) $allPostPutVars['pay_plan'];
        $client->amount = number_format($amount, 2, '.', '');
        if( $method=='add' ) {
            $client->created = date('Y-m-d h:i:s');
            $client->created_by = $allPostPutVars['modified_by'];
        } else {
            $client->edited = date('Y-m-d h:i:s');
            $client->edited_by = $allPostPutVars['modified_by'];
        }
	
	$client_id = \R::store( $client );
        
        return $client_id;
    }
            
    /**
     * Deletes a Client by ID.
     * 
     * @param type $id
     */
    public function delete( $id = null )
    {
        if( empty( $id ) ) {
           //
        } else {
           $client = \R::load( 'client', $id );
           \R::trash( $client );
        }
    }     
            
    
}
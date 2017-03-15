<?php

namespace SonoranAccounting\Models;

class Employee extends BaseModel {
    
    /**
     * Finds all Employees.
     * 
     * @return type
     */
    public function findAll() {
        return \R::findAll( 'employee', ' ORDER BY last_name ASC, first_name ASC ' );
    }
    
    /**
     * Finds an Employee by ID.
     * 
     * @param type $id
     * @return type
     */
    public function findById( $id )
    {
        return \R::findOne( 'employee', ' id = ? ', [ $id ] );
    }
    
    /**
     * Saves an Employee.
     * 
     * @param type $allPostPutVars
     * @return type
     */
    public function save( $allPostPutVars )
    {
        $method = ( empty( $allPostPutVars['id'] ) ? 'add' : 'edit' );

        $employee = ( ( $method=='add' ) ? \R::dispense( 'employee' ) : \R::load( 'employee', $allPostPutVars['id'] ) );
        $employee->first_name = (string) $allPostPutVars['first_name'];
        $employee->last_name = (string) $allPostPutVars['last_name'];
        $employee->address1 = (string) $allPostPutVars['address1'];
        $employee->address2 = (string) $allPostPutVars['address2'];
        $employee->city = (string) $allPostPutVars['city'];
        $employee->state = (string) $allPostPutVars['state'];
        $employee->zipcode = (string) $allPostPutVars['zipcode'];
        $employee->phone_number = (string) $allPostPutVars['phone_number'];
        $employee->pay_type = (string) $allPostPutVars['pay_type'];
        $employee->rate = number_format( $allPostPutVars['rate'], 2, '.', '');
        $employee->extension = (string) $allPostPutVars['extension'];
        if( $method=='add' ) {
            $employee->created = date('Y-m-d h:i:s');
            $employee->created_by = $allPostPutVars['modified_by'];
        } else {
            $employee->edited = date('Y-m-d h:i:s');
            $employee->edited_by = $allPostPutVars['modified_by'];
        }
        $date_of_hire = date( "Y-m-d", strtotime( $allPostPutVars['date_of_hire'] ) );
        $date_of_termination = date( "Y-m-d", strtotime( $allPostPutVars['date_of_termination'] ) );
        
        $employee->date_of_hire = $date_of_hire;
        $employee->date_of_termination = $date_of_termination;

        $employee_id = \R::store( $employee );
        
        return $employee_id;
    }
    
    /**
     * Deletes an Employee.
     * 
     * @param type $id
     */
    public function delete( $id = null )
    {
        if( empty( $id ) ) {
           //
        } else {
           $employee = \R::load( 'employee', $id );
           \R::trash( $employee );
        }
    }
    
}


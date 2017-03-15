<?php

namespace SonoranAccounting\Models;

class Time extends BaseModel {
    
    /**
     * Gets all Time entries.
     * 
     * @param type $employee_id
     * @return type
     */
    public function getAll( $employee_id = 0 ) {
        return \R::getAll(
            'SELECT time.id, time.date_worked, client.name AS client_name, time.hours FROM time
             LEFT JOIN client ON client.id = time.client_id
             WHERE time.employee_id = :employee_id AND time.timesheet_id IS NULL ORDER BY time.date_worked ASC, time.hours DESC, client.name ASC',
            [ ':employee_id' => $employee_id ] );
    }
    
    /**
     * Finds all Time entries by ID.
     * 
     * @param type $id
     * @return type
     */
    public function findById( $id )
    {
        return \R::findOne( 'time', ' id = ? ', [ $id ] );
    }
    
    /**
     * Finds all Time entries by Timesheet ID.
     * 
     * @param type $timesheetId
     * @return type
     */
    public function getAllByTimesheetId( $timesheetId )
    {
        return \R::getAll(
            'SELECT time.id, time.date_worked, client.name AS client_name, time.hours FROM time
             LEFT JOIN client ON client.id = time.client_id
             WHERE time.timesheet_id = :timesheet_id
             ORDER BY time.date_worked ASC, time.hours DESC, client.name ASC',
            [ ':timesheet_id' => $timesheetId ] );
    }
    
    /**
     * Saves a Time entry.
     * 
     * @param type $allPostPutVars
     * @return type
     */
    public function save( $allPostPutVars )
    {
        $method = ( empty( $allPostPutVars['id'] ) ? 'add' : 'edit' );

        $date_worked = date( "Y-m-d", strtotime( $allPostPutVars['date_worked'] ) );
        $hours = ( empty( $allPostPutVars['hours'] ) ? 0 : $allPostPutVars['hours'] );
        
        $time = ( ( $method=='add' ) ? \R::dispense( 'time' ) : \R::load( 'time', $allPostPutVars['id'] ) );
        $time->employee_id = $allPostPutVars['employee_id'];
        $time->date_worked = $date_worked;
        $time->client_id = $allPostPutVars['client_id'];
        $time->hours = number_format( $hours, 2, '.', '' );
        $time->created = date( 'Y-m-d h:i:s' );
        $time->created_by = $allPostPutVars['created_by'];

        $time_id = \R::store( $time );
        
        return $time_id;
    }
    
    /**
     * Deletes a Time entry.
     * 
     * @param type $id
     */
    public function delete( $id = null )
    {
        if( empty( $id ) ) {
           //
        } else {
           $time = \R::load( 'time', $id );
           \R::trash( $time );
        }
    }
    
    /**
     * Submits a Time entry.
     * 
     * @param type $employee_id
     * @param type $timesheet_id
     */
    public function submitTimeNow( $employee_id = 0, $timesheet_id = 0 )
    {
        \R::exec( 'update time set timesheet_id="' . $timesheet_id . '" where employee_id="' . $employee_id . '"' );
    }
    
}
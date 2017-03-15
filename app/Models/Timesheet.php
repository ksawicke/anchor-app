<?php

namespace SonoranAccounting\Models;

class Timesheet extends BaseModel {
    
    /**
     * Gets all Timesheets.
     * 
     * @return type
     */
    public function getAll() {
        return \R::getAll(
            'SELECT t.id, t.employee_id, t.created, t.created_by, e.first_name, e.last_name FROM timesheet t
             LEFT JOIN employee e ON t.employee_id = e.id
             ORDER BY t.id DESC',
            [ ] );
    }
    
    /**
     * Finds Timesheet by ID.
     * 
     * @param type $id
     * @return type
     */
    public function findById( $id )
    {
        return \R::findOne( 'timesheet', ' id = ? ', [ $id ] );
    }
    
    /**
     * Saves a Timesheet.
     * 
     * @param type $allPostPutVars
     * @return type
     */
    public function save( $allPostPutVars )
    {
        $method = ( empty( $allPostPutVars['id'] ) ? 'add' : 'edit' );

        $timesheet = ( ( $method=='add' ) ? \R::dispense( 'timesheet' ) : \R::load( 'time', $allPostPutVars['id'] ) );
        $timesheet->employee_id = $allPostPutVars['employee_id'];
        $timesheet->created = date( 'Y-m-d h:i:s' );
        $timesheet->created_by = $allPostPutVars['created_by'];

        $timesheet_id = \R::store( $timesheet );
        
        return $timesheet_id;
    }
    
}
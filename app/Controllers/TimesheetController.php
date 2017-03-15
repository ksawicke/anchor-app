<?php

namespace SonoranAccounting\Controllers;

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use Slim\Views\Twig as View;
use SonoranAccounting\Models\Time;
use SonoranAccounting\Models\Timesheet;

/**
 * Handles Timesheet page functionality on anchor.sonoranaccounting.com
 * 
 * Author: Kevin Sawicke <kevin@sonoranaccounting.com
 * Copyright: 2017 Sonoran Accounting Services LLC
 */
class TimesheetController extends AdminRoleController {
    
    
    public function __construct( $container )
    {
        parent::__construct( $container );
        
        $this->container->view->getEnvironment()->addGlobal( 'sonoranAccountingSession', $_SESSION['sonoranAccountingSession'] );
    }
    /**
     * Returns a view of all time.
     * 
     * @param Request $request
     * @param Response $response
     * @param type $args
     * @return type
     */
    public function index( Request $request, Response $response, $args ) 
    {     
        $Timesheet = new Timesheet;
        $Time = new Time;
        
        $data = [ 'timesheets' => $Timesheet->getAll() ];
        $data['count'] = count( $data['timesheets'] );
        
        foreach( $data['timesheets'] as $tsCounter => $timesheet ) {
            $timeEntries = $Time->getAllByTimesheetId( $timesheet['id'] );
            $data['timesheets'][$tsCounter]['entries'] = $timeEntries;
            
            foreach( $timeEntries as $id => $entry ) {
                $data['timesheets'][$tsCounter]['entries'][$id]['date_worked_mdy'] = date("m/d/Y", strtotime($entry['date_worked']));
            }
            
            $data['timesheets'][$tsCounter]['time_worked']['total'] = 0;
            $data['timesheets'][$tsCounter]['time_worked']['by_client'] = [];
            
            foreach( $timeEntries as $tCounter => $timeEntry ) {
                $clientName = $timeEntry['client_name'];
                $hours = $timeEntry['hours'];
                
                if( !array_key_exists( $clientName, $data['timesheets'][$tsCounter]['time_worked']['by_client'] ) ) {
                    $data['timesheets'][$tsCounter]['time_worked']['by_client'][$clientName] = 0;
                }
                
                $data['timesheets'][$tsCounter]['time_worked']['total'] += $hours;
                $data['timesheets'][$tsCounter]['time_worked']['by_client'][$clientName] += $hours;
            }
        }

        return $this->container->view->render( $response, 'timesheet/timesheet_index.twig', $data );
    }
    
    /**
     * Handles submitting a timesheet.
     * 
     * @param Request $request
     * @param Response $response
     * @param type $args
     * @return type
     */
    public function submit( Request $request, Response $response, $args ) 
    {
        return $this->container->view->render( $response, 'time/time_submit.twig', [] );
    }
    
    /**
     * Confirms submitting a timesheet.
     * 
     * @param Request $request
     * @param Response $response
     * @param type $args
     */
    public function submitConfirmed( Request $request, Response $response, $args ) 
    {
        $Timesheet = new Timesheet;
        
        $allPostPutVars = [
            'employee_id' => $_SESSION['sonoranAccountingSession']['employee_id'],
            'created_by' => $_SESSION['sonoranAccountingSession']['username']
        ];
        
        $timesheet_id = $Timesheet->save( $allPostPutVars );
        
        $Time = new Time;
        $Time->submitTimeNow( $_SESSION['sonoranAccountingSession']['employee_id'], $timesheet_id );
        
        header("Location: ../time/index");
        exit();
    }
    
    /**
     * Returns the view to add a timesheet.
     * 
     * @param Request $request
     * @param Response $response
     * @param type $args
     * @return type
     */
    public function add( Request $request, Response $response, $args )
    {
        if( empty( $args['id'] ) ) {
            $data = [];   
        } else {
            $Time = new Time;
            $data = [ 'time' => $Time->findById( $args['id'] ) ];
            foreach( $data['time'] as $time ) {
                $data['time']['date_worked_mdy'] = date("m/d/Y", strtotime($data['time']['date_worked']));
            }
        }

        $Client = new Client;
        $data['clients'] = $Client->findAll();

        return $this->container->view->render( $response, 'time/time_add.twig', $data );
    }
    
    /**
     * Saves a timesheet.
     * 
     * @param Request $request
     * @param Response $response
     * @param type $args
     */
    public function save( Request $request, Response $response, $args )
    {
        $allPostPutVars = $request->getParsedBody();
        
        $allPostPutVars['employee_id'] = $_SESSION['sonoranAccountingSession']['employee_id'];
        $allPostPutVars['created_by'] = $_SESSION['sonoranAccountingSession']['username'];
    
        $Time = new Time;
        
        $time_id = $Time->save( $allPostPutVars );
	
        header("Location: ../time/index");
        exit();
    }
    
    /**
     * Deletes a timesheet.
     * 
     * @param Request $request
     * @param Response $response
     * @param type $args
     */
    public function delete( Request $request, Response $response, $args )
    {
        $Time = new Time;
        $Time->delete( $args['id'] );
        
        header("Location: ../../time/index");
        exit();
    }
    
}
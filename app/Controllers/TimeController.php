<?php

namespace SonoranAccounting\Controllers;

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use Slim\Views\Twig as View;
use SonoranAccounting\Models\Time;
use SonoranAccounting\Models\Timesheet;
use SonoranAccounting\Models\Client;

/**
 * Handles Time page functionality on anchor.sonoranaccounting.com
 * 
 * Author: Kevin Sawicke <kevin@sonoranaccounting.com
 * Copyright: 2017 Sonoran Accounting Services LLC
 */
class TimeController extends EmployeeRoleController {
    
    
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
        $Time = new Time;
        $data = [ 'entries' => $Time->getAll( $_SESSION['sonoranAccountingSession']['employee_id'] ) ];
        $data['count'] = count( $data['entries'] );
        
        foreach( $data['entries'] as $id => $entry ) {
            $data['entries'][$id]['date_worked_mdy'] = date("m/d/Y", strtotime($entry['date_worked']));
        }

        return $this->container->view->render( $response, 'time/time_index.twig', $data );
    }
    
    /**
     * Returns the submit time view.
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
     * Handles employee submitting time.
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
     * Returns the add time view.
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
     * Saves time for employee.
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
     * Deletes a time entry.
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
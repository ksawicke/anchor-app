<?php

namespace SonoranAccounting\Controllers;

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use Slim\Views\Twig as View;
use SonoranAccounting\Models\Employee;
use SonoranAccounting\Models\User;
use Respect\Validation\Validator as v;

/**
 * Handles Employee page functionality on anchor.sonoranaccounting.com
 * 
 * Author: Kevin Sawicke <kevin@sonoranaccounting.com
 * Copyright: 2017 Sonoran Accounting Services LLC
 */
class EmployeeController extends AdminRoleController {
    
    
    public function __construct( $container )
    {
        parent::__construct( $container );
        
        $this->container->view->getEnvironment()->addGlobal( 'sonoranAccountingSession', $_SESSION['sonoranAccountingSession'] );
    }
    /**
     * Returns a view of all employees.
     * 
     * @param Request $request
     * @param Response $response
     * @param type $args
     * @return type
     */
    public function index( Request $request, Response $response, $args ) 
    {        
        $Employee = new Employee;
    	$data = [ 'employees' => $Employee->findAll() ];
    	$data['count'] = count( $data['employees'] );
        
    	return $this->container->view->render( $response, 'employee/employee_index.twig', $data );
    }
    
    /**
     * Displays the view to add an employee.
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
            $Employee = new Employee;
            $data = [ 'employee' => $Employee->findById( $args['id'] ) ];
            foreach( $data['employee'] as $time ) {
                $data['employee']['date_of_hire_mdy'] = ( !empty( $data['employee']['date_of_hire'] ) ?
                    date( "m/d/Y", strtotime( $data['employee']['date_of_hire'] ) ) : '' );
                $data['employee']['date_of_termination_mdy'] = ( !empty( $data['employee']['date_of_termination'] ) ?
                    date( "m/d/Y", strtotime( $data['employee']['date_of_termination'] ) ) : '' );
            }
            
            $User = new User;
            $user_row = $User->getRowById( $args['id'] );
            $data['employee']['username'] = $user_row['username'];
            $data['employee']['email_address'] = $user_row['email_address'];
            $data['employee']['role'] = $user_row['role'];
        }
	
	return $this->container->view->render( $response, 'employee/employee_add.twig', $data );
    }
    
    /**
     * Saves an employee record.
     * 
     * @param Request $request
     * @param Response $response
     * @param type $args
     * @return type
     */
    public function save( Request $request, Response $response, $args )
    {
        $allPostPutVars = $request->getParsedBody();
        
        $validation = $this->container->validator->validate( $request, [
            'first_name' => v::noWhitespace()->notEmpty()->alpha(),
            'last_name' => v::noWhitespace()->notEmpty()->alpha(),
            'email_address' => v::noWhitespace()->email()
        ]);
        
        if( $validation->failed() === true ) {
            $this->container->view->getEnvironment()->addGlobal( 'errors', $_SESSION['errors'] );
            unset( $_SESSION['errors'] );

            return $this->container->view->render( $response, 'employee/employee_add.twig' );
        }
        
        $allPostPutVars['modified_by'] = $_SESSION['sonoranAccountingSession']['username'];
        
        $Employee = new Employee;
        $User = new User;
        
        $allPostPutVars['employee_id'] = $Employee->save( $allPostPutVars );
        
        $User->save( $allPostPutVars );
        
        header("Location: ../employee/index");
        exit();
    }
    
    /**
     * Deletes an employee.
     * 
     * @param Request $request
     * @param Response $response
     * @param type $args
     */
    public function delete( Request $request, Response $response, $args )
    {
        $Employee = new Employee;
        $Employee->delete( $args['id'] );
        
        header("Location: ../../employee/index");
        exit();
    }
    
}
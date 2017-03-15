<?php

namespace SonoranAccounting\Controllers;

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use Slim\Views\Twig as View;
use SonoranAccounting\Models\Client;

/**
 * Handles Client page functionality on anchor.sonoranaccounting.com
 * 
 * Author: Kevin Sawicke <kevin@sonoranaccounting.com>
 * Copyright: 2017 Sonoran Accounting Services LLC
 */
class ClientController extends AdminRoleController {
    
    public function __construct( $container )
    {
        parent::__construct( $container );
        
        $this->container->view->getEnvironment()->addGlobal( 'sonoranAccountingSession', $_SESSION['sonoranAccountingSession'] );
    }
    /**
     * Returns a view of all clients.
     * 
     * @param Request $request
     * @param Response $response
     * @param type $args
     * @return object
     */
    public function index( Request $request, Response $response, $args ) 
    {     
        $Client = new Client;
        
        $data = [ 'clients' => $Client->findAll() ];
        $data['count'] = count( $data['clients'] );
    
	return $this->container->view->render( $response, 'client/client_index.twig', $data );
    }
    
    /**
     * Returns the new client view.
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
            $Client = new Client;
            
            $data = [ 'client' => $Client->findById( $args['id'] ) ];
        }

        return $this->container->view->render( $response, 'client/client_add.twig', $data );
    }
    
    /**
     * Saves a new client.
     * 
     * @param Request $request
     * @param Response $response
     * @param type $args
     */
    public function save( Request $request, Response $response, $args )
    {
        $allPostPutVars = $request->getParsedBody();
        
        $allPostPutVars['modified_by'] = $_SESSION['sonoranAccountingSession']['username'];
    
        $Client = new Client;
        
        $allPostPutVars['client_id'] = $Client->save( $allPostPutVars );
        
        header("Location: ../client/index");
        exit();
    }
    
    /**
     * Deletes a new client.
     * 
     * @param Request $request
     * @param Response $response
     * @param type $args
     */
    public function delete( Request $request, Response $response, $args )
    {
        $Client = new Client;
        $Client->delete( $args['id'] );
        
        header("Location: ../../client/index");
        exit();
    }
    
}
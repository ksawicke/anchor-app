<?php

namespace SonoranAccounting\Controllers\Auth;

use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;
use Slim\Views\Twig as View;
use SonoranAccounting\Controllers\BaseController;
use Respect\Validation\Validator as v;

/**
 * Handles login and logout of users on anchor.sonoranaccounting.com
 * 
 * Author: Kevin Sawicke <kevin@sonoranaccounting.com
 * Copyright: 2017 Sonoran Accounting Services LLC
 */
class AuthController extends BaseController {
    
    public function getLogin( Request $request, Response $response )
    {     
        return $this->container->view->render( $response, 'auth/login.twig' );
    }
    
    public function postLogin( Request $request, Response $response )
    {
        $validation = $this->container->validator->validate( $request, [
            'username' => v::noWhitespace()->notEmpty()->alpha(),
            'password' => v::noWhitespace()->notEmpty()
        ]);
        
        if( $validation->failed() === true ) {
            $this->container->view->getEnvironment()->addGlobal( 'errors', $_SESSION['errors'] );
            unset( $_SESSION['errors'] );
            return $this->container->view->render( $response, 'auth/login.twig' );
        }
        
        if ( $request->getAttribute( 'csrf_result' ) === false ) {
            $_SESSION['errors'] = [ 'username' => [ 0 => 'Form was not valid' ] ];
            $this->container->view->getEnvironment()->addGlobal( 'errors', $_SESSION['errors'] );
            unset( $_SESSION['errors'] );
            return $this->container->view->render( $response, 'auth/login.twig' );
        }
        
        $auth = $this->container->auth->attempt(
            $request->getParam( 'username' ),
            $request->getParam( 'password' )
        );

        if( $auth === false ) {
            $_SESSION['errors'] = [ 'username' => [ 0 => 'Invalid username/password' ] ];
            $this->container->view->getEnvironment()->addGlobal( 'errors', $_SESSION['errors'] );
            unset( $_SESSION['errors'] );
            return $this->container->view->render( $response, 'auth/login.twig' );
        }
        
        $this->container->view->getEnvironment()->addGlobal( 'sonoranAccountingSession', $_SESSION['sonoranAccountingSession'] );
        
        switch( $_SESSION['sonoranAccountingSession']['role'] ) {
            case 'Admin':
                header("Location: ../client/index");
                break;
            
            case 'Employee':
            default:
                header("Location: ../time/index");
                break;
        }
        
        exit();
    }
    
    public function logout( Request $request, Response $response )
    {
        header("Location: ../auth/login");
        exit();
    }
}
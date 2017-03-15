<?php

namespace SonoranAccounting\Middleware;

class ValidationErrorsMiddleware extends BaseMiddleware {
    
    public function __invoke( $request, $response, $next )
    {
        /** Future...
//        if (isset($_SESSION['errors']))
//        {
//            $this->container->view->getEnvironment()->addGlobal('errors', $_SESSION['errors']);
//            unset($_SESSION['errors']);
//        }
        
//        $errors = [ 'username' => [ 0 => 'always an error', 1 => 'another error' ] ];
      
//        $this->container->view->getEnvironment()->addGlobal( 'errors', $errors );
        
//        echo '<pre>SESSION errors';
//        var_dump( $errors );
//        echo '</pre>'; //
        
//        unset( $_SESSION['errors'] );
         ****/
        
        $reponse = $next( $request, $response );
        
        return $response;
    }
    
}


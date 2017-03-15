<?php

namespace SonoranAccounting\Middleware;

class CsrfViewMiddleware extends BaseMiddleware {
    
    public function __invoke( $request, $response, $next )
    {
        $this->container->view->getEnvironment()->addGlobal( 'csrf', [
            'field' => '<input type="hidden" name="' . $this->container->csrf->getTokenNameKey() .
                '" value="' . $this->container->csrf->getTokenName() . '"><input type="hidden" name="' . $this->container->csrf->getTokenValueKey() .
                '" value="' . $this->container->csrf->getTokenValue() . '">'
        ]);
        
        $reponse = $next( $request, $response );
        
        return $response;
    }
    
}


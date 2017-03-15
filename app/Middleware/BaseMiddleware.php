<?php

namespace SonoranAccounting\Middleware;

class BaseMiddleware {
    
    protected $container;
    
    public function __construct( $container )
    {
        $this->container = $container;
    }
    
}


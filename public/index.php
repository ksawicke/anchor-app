<?php

session_start();

require '../vendor/autoload.php';

date_default_timezone_set('America/Phoenix');

error_reporting(E_ALL);
ini_set("display_errors", 1);

$config = [
    'settings' => [
        'displayErrorDetails' => true,
        'logger' => [
            'name' => 'slim-app',
            'level' => Monolog\Logger::DEBUG,
            'path' => __DIR__ . '/../logs/app.log',
        ],
//        'db' => [
//            'driver' => 'mysql',
//            'host' => 'localhost',
//            'database' => 'sonoran_office',
//            'username' => 'sonoran_office',
//            'password' => '{)2U(Ns]1PsI',
//            'charset' => 'utf8',
//            'collation' => 'utf8_unicode_ci',
//            'prefix' => '',
//        ]
    ],
];

$app = new \Slim\App($config);

// Get container
$container = $app->getContainer();

// TODO: Save for later. If we wanted to use
// Eloquent, from Laravel Framework, instead to manage the database.
// 
//$capsule = new \Illuminate\Database\Capsule\Manager;
//$capsule->addConnection($container['settings']['db']);
//$capsule->setAsGlobal();
//$capsule->bootEloquent();
//
//$container['db'] = function ($container) use ($capsule) {
//    return $capsule;
//};

// Register component on container
$container['view'] = function ( $container ) {
    $view = new \Slim\Views\Twig(__DIR__ . '/../resources/views', [
        'cache' => false
    ]);
    
    // Instantiate and add Slim specific extension
    // $basePath = rtrim(str_ireplace('index.php', '', $container['request']->getUri()->getBasePath()), '/');
    $view->addExtension(new \Slim\Views\TwigExtension(
        $container->router,
        $container->request->getUri()
    ));

    return $view;
};

$container['validator'] = function ( $container ) {
    return new \SonoranAccounting\Validation\Validator( $container );
};

$container['EmployeeController'] = function ( $container ) {
    return new \SonoranAccounting\Controllers\EmployeeController( $container );
};

$container['ClientController'] = function ( $container ) {
    return new \SonoranAccounting\Controllers\ClientController( $container );
};

$container['TimeController'] = function ( $container ) {
    return new \SonoranAccounting\Controllers\TimeController( $container );
};

$container['TimesheetController'] = function ( $container ) {
    return new \SonoranAccounting\Controllers\TimesheetController( $container );
};

$container['AuthController'] = function ( $container ) {
    return new \SonoranAccounting\Controllers\Auth\AuthController( $container );
};

$container['csrf'] = function ( $container ) {
    return new \Slim\Csrf\Guard;
};

$container['auth'] = function ( $container ) {
    return new \SonoranAccounting\Auth\Auth;
};

$app->add( new \SonoranAccounting\Middleware\CsrfViewMiddleware( $container ) );

$app->add( new \SonoranAccounting\Middleware\ValidationErrorsMiddleware( $container ) );

$app->add( $container->csrf );

// Set up dependencies
//require __DIR__ . '/../src/dependencies.php';

// Register middleware
//require __DIR__ . '/../src/middleware.php';

// Register routes
require __DIR__ . '/../app/routes.php';

$app->run();

/*****
if (PHP_SAPI == 'cli-server') {
    // To help the built-in PHP dev server, check if the request was actually for
    // something which should probably be served as a static file
    $url  = parse_url($_SERVER['REQUEST_URI']);
    $file = __DIR__ . $url['path'];
    if (is_file($file)) {
        return false;
    }
}

require __DIR__ . '/../vendor/autoload.php';

session_start();

// Instantiate the app
$settings = require __DIR__ . '/../src/settings.php';
$app = new \Slim\App($settings);

// Set up dependencies
require __DIR__ . '/../src/dependencies.php';

// Register middleware
require __DIR__ . '/../src/middleware.php';

// Register routes
require __DIR__ . '/../src/routes.php';

// Run app
$app->run();
*****/
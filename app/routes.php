<?php

use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

/**
 * Application routes
 **/

$app->get('/', function (Request $request, Response $response, $args) {
    //return $response->withRedirect($this->router->pathFor('viewEmployees'));
    return $response->withStatus(302)->withHeader( 'Location', '/sonoran/public/auth/login' );
});


// LOG IN

$app->get( '/auth/login', 'AuthController:getLogin' )->setName( 'auth.login' );

$app->post( '/auth/login', 'AuthController:postLogin' )->setName( 'auth.post' );

$app->get( '/auth/logout', 'AuthController:logout' )->setName( 'auth.logout' );


// EMPLOYEES

$app->get( '/employee/index', 'EmployeeController:index' )->setName( 'employee.view' );

$app->get( '/employee/add[/{id}]', 'EmployeeController:add' )->setName( 'employee.add' );

$app->post( '/employee/add', 'EmployeeController:save' )->setName( 'employee.save' );

$app->get( '/employee/delete[/{id}]', 'EmployeeController:delete' )->setName( 'employee.delete' );


// CLIENTS

$app->get( '/client/index', 'ClientController:index' )->setName( 'client.view' );

$app->get( '/client/add[/{id}]', 'ClientController:add' )->setName( 'client.add' );

$app->post( '/client/add', 'ClientController:save' )->setName( 'client.save' );

$app->get( '/client/delete/{id}', 'ClientController:delete' )->setName( 'client.delete' );


// TIME

$app->get( '/time/index', 'TimeController:index' )->setName( 'time.view' );

$app->get( '/time/add[/{id}]', 'TimeController:add' )->setName( 'time.add' );

$app->post( '/time/add', 'TimeController:save' )->setName( 'time.save' );

$app->get( '/time/delete/{id}', 'TimeController:delete' )->setName( 'time.delete' );

$app->get( '/time/submit', 'TimeController:submit' )->setName( 'time.submit' );

$app->get( '/time/submitConfirmed', 'TimeController:submitConfirmed' )->setName( 'time.submitConfirmed' );



// TIMESHEETS

$app->get( '/timesheet/index', 'TimesheetController:index' )->setName( 'timesheet.view' );
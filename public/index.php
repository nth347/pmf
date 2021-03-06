<?php

/**
 * Front controller
 *
 * PHP version 7.4
 */

/**
 * Composer
 */
require "../vendor/autoload.php";

/**
 * Error and Exception handling
 */
error_reporting(E_ALL);
set_error_handler('Core\Error::errorHandler');
set_exception_handler('Core\Error::exceptionHandler');

/**
 * Routing
 */
// We don't need the following require statement anymore, because we already have Composer
//require  '../Core/Router.php';

$router = new Core\Router();

/**
 * Add the routes
 * A home/index route
 * A variable route with format controller/action, e.g. posts/new
 * A variable route with format controller/id/action, e.g. posts/100/edit
 * A variable route with format admin/controller/action, e.g. admin/products/add
 */
$router->add('', ['controller' => 'Home', 'action' => 'index']);
$router->add('{controller}/{action}');
$router->add('{controller}/{id:\d+}/{action}');
$router->add('admin/{controller}/{action}', ['namespace' => 'Admin']);

/*
// Display the routing table
echo '<br>ROUTING TABLE<br>';
echo '<pre>';
var_dump($router->getRoutes());
echo '</pre>';

// Match the requested route
echo '<br>MATCH THE REQUESTED ROUTE<br>';
if ($router->match($url)) {
    echo '<pre>';
    var_dump($router->getParams());
    echo '<pre>';
}
else {
    echo '<pre>';
    echo 'No route is found<br>';
    var_dump($router->getParams());
    echo '<pre>';
}
*/

$router->dispatch($_SERVER['QUERY_STRING']);

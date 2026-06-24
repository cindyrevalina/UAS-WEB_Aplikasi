<?php

use CodeIgniter\Router\RouteCollection;

/** @var RouteCollection $routes */
$routes->get('/', 'Home::index');
$routes->post('login', 'AuthController::login'); 
$routes->post('logout', 'AuthController::logout');
$routes->get('reports', 'ReportController::index');
$routes->post('reports', 'ReportController::create', ['filter' => 'auth']);
$routes->options('(:any)', 'AuthController::index');
$routes->put('reports/(:num)', 'ReportController::update/$1', ['filter' => 'auth']);
$routes->delete('reports/(:num)', 'ReportController::delete/$1', ['filter' => 'auth']);
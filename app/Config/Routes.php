<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */

$routes->get('/', 'Index::index');

$routes->get('login', 'Auth::login');
$routes->post('login', 'Auth::attempt');
$routes->get('logout', 'Auth::logout');

$routes->get('password/forgot', 'Auth::forgot');
$routes->post('password/forgot', 'Auth::sendReset');

$routes->get('password/reset/(:any)', 'Auth::reset/$1');
$routes->post('password/reset', 'Auth::updatePassword');

$routes->get('register', 'Auth::register');
$routes->post('register/submit', 'Auth::submitRegister');

// User Management (Admin Console)
$routes->get('users', 'AdminController::users');






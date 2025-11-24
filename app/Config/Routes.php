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
$routes->get('equipment', 'AdminController::equipment');

$routes->get('/borrow', 'BorrowController::index');
$routes->post('/borrow/submit', 'BorrowController::submit');

$routes->get('/return', 'ReturnController::index');
$routes->post('/return/submit', 'ReturnController::submit');

$routes->get('/reservation', 'ReservationController::index');
$routes->post('/reservation/submit', 'Reservation::submitReservation');

$routes->get('/reports', 'ReportsController::index');

//for register
$routes->get('register', 'Auth::register');
$routes->post('register/submit', 'Auth::submitRegister');

// Reservation routes
$routes->get('reservation', 'ReservationController::index');
$routes->post('reservation/submitReservation', 'ReservationController::submitReservation');

//for editing user details
$routes->post('admin/updateUser', 'AdminController::updateUser');

//for viewing user details
$routes->get('admin/user/(:num)', 'AdminController::getUser/$1');
// Toggle user active/inactive
$routes->post('admin/toggleUser', 'AdminController::toggleUser');

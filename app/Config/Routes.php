<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */

// ============================================
// HOME
// ============================================
$routes->get('/', 'Index::index');

// ============================================
// AUTHENTICATION
// ============================================
$routes->get('login', 'Auth::login');
$routes->post('login', 'Auth::attempt');
$routes->get('logout', 'Auth::logout');

// ============================================
// PASSWORD RESET
// ============================================
$routes->get('password/forgot', 'Auth::forgot');
$routes->post('password/forgot', 'Auth::sendReset');
$routes->get('password/reset/(:any)', 'Auth::reset/$1');
$routes->post('password/reset', 'Auth::updatePassword');

// ============================================
// REGISTRATION & VERIFICATION
// ============================================
$routes->get('register', 'Auth::register');
$routes->post('register/submit', 'Auth::submitRegister');
$routes->get('auth/verify/(:any)', 'Auth::verify/$1');

// ============================================
// USER MANAGEMENT (Admin Console)
// ============================================
$routes->get('users', 'AdminController::users');
$routes->get('admin/user/(:num)', 'AdminController::getUser/$1');
$routes->post('admin/addUser', 'AdminController::addUser');
$routes->post('admin/updateUser', 'AdminController::updateUser');
$routes->post('admin/toggleUser', 'AdminController::toggleUser');

// ============================================
// EQUIPMENT MANAGEMENT
// ============================================
$routes->get('equipment', 'EquipmentController::index');
$routes->get('equipment/get/(:num)', 'EquipmentController::get/$1');
$routes->get('equipment/getGroupItems', 'EquipmentController::getGroupItems');
$routes->get('equipment/getGroupView', 'EquipmentController::getGroupView');
$routes->post('equipment/add', 'EquipmentController::add');
$routes->post('equipment/update/(:num)', 'EquipmentController::update/$1');
$routes->post('equipment/toggleStatus/(:num)', 'EquipmentController::toggleStatus/$1');

// ============================================
// BORROW
// ============================================
$routes->get('/borrow', 'BorrowController::index');
$routes->post('/borrow/submit', 'BorrowController::submit');

// ============================================
// RETURN
// ============================================
$routes->get('/return', 'ReturnController::index');
$routes->post('/return/submit', 'ReturnController::submit');

// ============================================
// RESERVATION
// ============================================
$routes->get('/reservation', 'ReservationController::index');
$routes->post('/reservation/submit', 'ReservationController::submitReservation');
$routes->post('reservation/submitReservation', 'ReservationController::submitReservation');

// ============================================
// REPORTS
// ============================================
$routes->get('/reports', 'ReportsController::index');

// ============================================
// ABOUT
// ============================================
$routes->get('/about', 'AboutController::index');

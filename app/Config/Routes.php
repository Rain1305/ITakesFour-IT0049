<?php

use CodeIgniter\Router\RouteCollection;

/** @var RouteCollection $routes */

// Redirect root to login (AuthController redirects to /products if already logged in)
$routes->get('/', 'AuthController::login');

// Public Authentication Routes
$routes->get('login', 'AuthController::login');
$routes->post('login', 'AuthController::authenticate');
$routes->get('logout', 'AuthController::logout');

// Protected Management Routes (Requires Logged-In Staff)
$routes->group('', ['filter' => 'auth'], static function ($routes) {
    // Product Management
    $routes->get('products', 'ProductController::index');
    $routes->get('products/create', 'ProductController::create');
    $routes->post('products/store', 'ProductController::store');
    $routes->get('products/edit/(:num)', 'ProductController::edit/$1');
    $routes->post('products/update/(:num)', 'ProductController::update/$1');
    $routes->post('products/delete/(:num)', 'ProductController::delete/$1');

    // Customer Management
    $routes->get('customers', 'CustomerController::index');
    $routes->get('customers/create', 'CustomerController::create');
    $routes->post('customers/store', 'CustomerController::store');
    $routes->get('customers/edit/(:num)', 'CustomerController::edit/$1');
    $routes->post('customers/update/(:num)', 'CustomerController::update/$1');
    $routes->post('customers/delete/(:num)', 'CustomerController::delete/$1');

    // Staff (User) Management
    $routes->get('users', 'UserController::index');
    $routes->get('users/create', 'UserController::create');
    $routes->post('users/store', 'UserController::store');
    $routes->get('users/edit/(:num)', 'UserController::edit/$1');
    $routes->post('users/update/(:num)', 'UserController::update/$1');
    $routes->post('users/delete/(:num)', 'UserController::delete/$1');

    // Sales Management (Record Sale & Sales History)
    $routes->get('sales', 'SaleController::index');
    $routes->get('sales/create', 'SaleController::create');
    $routes->post('sales/store', 'SaleController::store');
    $routes->post('sales/delete/(:num)', 'SaleController::delete/$1');

});
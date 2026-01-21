<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'Home::index');

$routes->options('(:any)', static function () {
    // Implement processing for OPTIONS if necessary
    $response = service('response');
    $response->setStatusCode(200);
    return $response;
});

// Shield Auth Routes
// Override Register to use custom controller
$routes->get('register', '\App\Controllers\Auth\RegisterController::registerView');
$routes->post('register', '\App\Controllers\Auth\RegisterController::registerAction');

service('auth')->routes($routes);


$routes->get('dashboard', 'Home::dashboard', ['filter' => 'auth']);

// Profile Routes
$routes->group('profile', ['filter' => 'auth'], function($routes) {
    $routes->get('/', 'ProfileController::index');
    $routes->get('edit', 'ProfileController::edit');
    $routes->post('update', 'ProfileController::update');
    $routes->get('password', 'ProfileController::password');
    $routes->post('update-password', 'ProfileController::updatePassword');
});

$routes->group('mahasiswa', ['filter' => 'auth'], function($routes) {
    $routes->get('/', 'MahasiswaController::index');
    $routes->get('create', 'MahasiswaController::create');
    $routes->post('store', 'MahasiswaController::store');
    $routes->get('edit/(:num)', 'MahasiswaController::edit/$1');
    $routes->post('update/(:num)', 'MahasiswaController::update/$1');
    $routes->get('delete/(:num)', 'MahasiswaController::delete/$1');
});

// RBAC Routes with Permission Checking
$routes->group('roles', ['filter' => 'auth'], function($routes) {
    $routes->get('/', 'RoleController::index', ['filter' => 'permission:roles.view']);
    $routes->get('create', 'RoleController::create', ['filter' => 'permission:roles.create']);
    $routes->post('store', 'RoleController::store', ['filter' => 'permission:roles.create']);
    $routes->get('show/(:num)', 'RoleController::show/$1', ['filter' => 'permission:roles.view']);
    $routes->get('(:num)', 'RoleController::show/$1', ['filter' => 'permission:roles.view']);
    $routes->get('(:num)/edit', 'RoleController::edit/$1', ['filter' => 'permission:roles.edit']);
    $routes->post('(:num)/update', 'RoleController::update/$1', ['filter' => 'permission:roles.edit']);
    $routes->get('(:num)/delete', 'RoleController::delete/$1', ['filter' => 'permission:roles.delete']);
    $routes->post('(:num)/toggle', 'RoleController::toggle/$1', ['filter' => 'permission:roles.edit']);
});

$routes->group('permissions', ['filter' => 'auth'], function($routes) {
    $routes->get('/', 'PermissionController::index', ['filter' => 'permission:permissions.view']);
    $routes->get('create', 'PermissionController::create', ['filter' => 'permission:permissions.create']);
    $routes->post('store', 'PermissionController::store', ['filter' => 'permission:permissions.create']);
    $routes->get('show/(:num)', 'PermissionController::show/$1', ['filter' => 'permission:permissions.view']);
    $routes->get('(:num)', 'PermissionController::show/$1', ['filter' => 'permission:permissions.view']);
    $routes->get('(:num)/edit', 'PermissionController::edit/$1', ['filter' => 'permission:permissions.edit']);
    $routes->post('(:num)/update', 'PermissionController::update/$1', ['filter' => 'permission:permissions.edit']);
    $routes->get('(:num)/delete', 'PermissionController::delete/$1', ['filter' => 'permission:permissions.delete']);
    $routes->post('(:num)/toggle', 'PermissionController::toggle/$1', ['filter' => 'permission:permissions.edit']);
});

$routes->group('users', ['filter' => 'auth'], function($routes) {
    $routes->get('/', 'UserController::index', ['filter' => 'permission:users.view']);
    $routes->get('create', 'UserController::create', ['filter' => 'permission:users.create']);
    $routes->post('store', 'UserController::store', ['filter' => 'permission:users.create']);
    $routes->get('(:num)', 'UserController::show/$1', ['filter' => 'permission:users.view']);
    $routes->get('(:num)/edit', 'UserController::edit/$1', ['filter' => 'permission:users.edit']);
    $routes->post('(:num)/update', 'UserController::update/$1', ['filter' => 'permission:users.edit']);
    $routes->get('(:num)/delete', 'UserController::delete/$1', ['filter' => 'permission:users.delete']);
    $routes->post('(:num)/toggle', 'UserController::toggle/$1', ['filter' => 'permission:users.edit']);
});

// Menu Management Routes
$routes->group('menus', ['filter' => 'auth'], function($routes) {
    $routes->get('/', 'MenuController::index', ['filter' => 'permission:users.view']);
    $routes->get('create', 'MenuController::create', ['filter' => 'permission:users.view']);
    $routes->post('store', 'MenuController::store', ['filter' => 'permission:users.view']);
    $routes->get('edit/(:num)', 'MenuController::edit/$1', ['filter' => 'permission:users.view']);
    $routes->post('update/(:num)', 'MenuController::update/$1', ['filter' => 'permission:users.view']);
    $routes->post('delete/(:num)', 'MenuController::delete/$1', ['filter' => 'permission:users.view']);
    $routes->post('toggle-status/(:num)', 'MenuController::toggleStatus/$1', ['filter' => 'permission:users.view']);
});



$routes->group('mahasiswa', ['filter' => 'auth'], function($routes) {
    $routes->get('/', 'MahasiswaController::index', ['filter' => 'permission:mahasiswa.view']);
    $routes->get('create', 'MahasiswaController::create', ['filter' => 'permission:mahasiswa.create']);
    $routes->post('store', 'MahasiswaController::store', ['filter' => 'permission:mahasiswa.create']);
    $routes->get('edit/(:num)', 'MahasiswaController::edit/$1', ['filter' => 'permission:mahasiswa.edit']);
    $routes->post('update/(:num)', 'MahasiswaController::update/$1', ['filter' => 'permission:mahasiswa.edit']);
    $routes->get('delete/(:num)', 'MahasiswaController::delete/$1', ['filter' => 'permission:mahasiswa.delete']);
});

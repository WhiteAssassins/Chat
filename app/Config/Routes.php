<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'ChatController::home');
$routes->match(['get', 'post'], 'login', 'AuthController::login');
$routes->match(['get', 'post'], 'register', 'AuthController::register');
$routes->post('logout', 'AuthController::logout', ['filter' => 'auth']);

$routes->group('chat', ['filter' => 'auth'], static function ($routes) {
    $routes->get('/', 'ChatController::index');
    $routes->get('poll', 'ChatController::poll');
    $routes->post('messages', 'ChatController::store');
});

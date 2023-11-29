<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'Home::index');

$routes->group('api', static function ($routes) {
    $routes->group('v1', static function ($routes) {
        $routes->group('vendors', static function ($routes) {
            $routes->delete('(:num)', 'Api\StudentController::delete/$1');
            $routes->post('/', 'Api\VendorController::store');
            $routes->get('/', 'Api\StudentController::index');
            $routes->get('(:num)', 'Api\StudentController::show/$1');
            $routes->put('(:num)', 'Api\VendorController::update/$1');
        });
    });
});

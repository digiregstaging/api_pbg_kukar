<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'Home::index');

$routes->group('api', static function ($routes) {
    $routes->group('v1', static function ($routes) {
        $routes->group('', ['filter' => "auth"], static function ($routes) {
            $routes->group('vendors', static function ($routes) {
                $routes->delete('(:num)', 'Api\VendorController::delete/$1');
                $routes->post('/', 'Api\VendorController::store');
                $routes->get('/', 'Api\VendorController::getAll');
                $routes->get('(:num)', 'Api\VendorController::get/$1');
                $routes->put('(:num)', 'Api\VendorController::update/$1');
            });

            $routes->group('budgets', static function ($routes) {
                $routes->delete('(:num)', 'Api\BudgetController::delete/$1');
                $routes->post('/', 'Api\BudgetController::store');
                $routes->get('/', 'Api\BudgetController::getAll');
                $routes->get('(:num)', 'Api\BudgetController::get/$1');
                $routes->put('(:num)', 'Api\BudgetController::update/$1');
            });

            $routes->group('programs', static function ($routes) {
                $routes->delete('(:num)', 'Api\ProgramController::delete/$1');
                $routes->post('/', 'Api\ProgramController::store');
                $routes->get('/', 'Api\ProgramController::getAll');
                $routes->get('(:num)', 'Api\ProgramController::get/$1');
                $routes->put('(:num)', 'Api\ProgramController::update/$1');
            });

            $routes->group('users', static function ($routes) {
                $routes->delete('(:num)', 'Api\UserController::delete/$1');
                $routes->post('/', 'Api\UserController::store');
                $routes->get('/', 'Api\UserController::getAll');
                $routes->get('(:num)', 'Api\UserController::get/$1');
                $routes->put('(:num)', 'Api\UserController::update/$1');
            });


            $routes->post('auth/logout', 'Api\AuthController::logout');
        });
        $routes->post('auth/login', 'Api\AuthController::login');
    });
});

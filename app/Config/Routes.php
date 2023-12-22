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

            $routes->group('kecamatan', static function ($routes) {
                $routes->delete('(:num)', 'Api\KecamatanController::delete/$1');
                $routes->post('/', 'Api\KecamatanController::store');
                $routes->get('/', 'Api\KecamatanController::getAll');
                $routes->get('(:num)', 'Api\KecamatanController::get/$1');
                $routes->put('(:num)', 'Api\KecamatanController::update/$1');
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

            $routes->group('projects', static function ($routes) {
                $routes->delete('(:num)', 'Api\ProjectController::delete/$1');
                $routes->post('/', 'Api\ProjectController::store');
                $routes->get('/', 'Api\ProjectController::getAll');
                $routes->get('(:num)', 'Api\ProjectController::get/$1');
                $routes->put('(:num)', 'Api\ProjectController::update/$1');
                $routes->put('update-status/(:num)', 'Api\ProjectController::updateStatusProject/$1');
                $routes->get('get-graph/(:num)', 'Api\ProjectController::getDateForGraph/$1');
            });

            $routes->group('vendor-history', static function ($routes) {
                $routes->delete('(:num)', 'Api\VendorHistoryController::delete/$1');
                $routes->post('/', 'Api\VendorHistoryController::store');
                $routes->get('/', 'Api\VendorHistoryController::getAll');
                $routes->get('(:num)', 'Api\VendorHistoryController::get/$1');
                $routes->put('(:num)', 'Api\VendorHistoryController::update/$1');
            });

            $routes->group('project-progress', static function ($routes) {
                $routes->delete('(:num)', 'Api\ProjectProgressController::delete/$1');
                $routes->post('/', 'Api\ProjectProgressController::store');
                $routes->get('/', 'Api\ProjectProgressController::getAll');
                $routes->get('(:num)', 'Api\ProjectProgressController::get/$1');
                $routes->put('(:num)', 'Api\ProjectProgressController::update/$1');
                $routes->put('update-status/(:num)', 'Api\ProjectProgressController::updateStatusProjectProgress/$1');
            });

            $routes->group('project-payments', static function ($routes) {
                $routes->delete('(:num)', 'Api\ProjectPaymentController::delete/$1');
                $routes->post('/', 'Api\ProjectPaymentController::store');
                $routes->get('/', 'Api\ProjectPaymentController::getAll');
                $routes->get('(:num)', 'Api\ProjectPaymentController::get/$1');
                $routes->put('(:num)', 'Api\ProjectPaymentController::update/$1');
                $routes->put('update-status/(:num)', 'Api\ProjectPaymentController::updateStatusProjectPayment/$1');
            });

            $routes->group('documents', static function ($routes) {
                $routes->delete('(:num)', 'Api\DocumentController::delete/$1');
                $routes->post('/', 'Api\DocumentController::store');
                $routes->get('/', 'Api\DocumentController::getAll');
                // $routes->get('(:num)', 'Api\DocumentController::get/$1');
                // $routes->put('(:num)', 'Api\DocumentController::update/$1');
            });

            $routes->post('auth/logout', 'Api\AuthController::logout');
            $routes->post('auth/reset-password/(:num)', 'Api\AuthController::resetPassword/$1');
            $routes->post('auth/change-password/(:num)', 'Api\AuthController::changePassword/$1');
        });
        $routes->post('auth/login', 'Api\AuthController::login');
    });
});

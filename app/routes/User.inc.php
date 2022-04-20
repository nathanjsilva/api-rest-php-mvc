<?php

namespace App\Routes;

use App\Controllers\UserController;
use Utils\Router\Request;
use Utils\Router\Router;

/**
 * @var Router $router
 */

$router->post('/user/add', fn (Request $request) => (new UserController())->add($request->getData()));
$router->post('/user/token', fn (Request $request) => (new UserController())->login($request->getData()));
$router->put('/update/user', fn (Request $request) => (new UserController())->updateUser($request->getData()));
$router->delete('/delete/user', fn (Request $request) => (new UserController())->deleteUser($request->getData()));

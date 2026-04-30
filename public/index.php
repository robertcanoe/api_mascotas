<?php

declare(strict_types=1);

use App\Controllers\ApiController;
use App\Core\Dispatcher;
use App\Core\Router;
use App\Middleware\JwtAuthMiddleware;

require dirname(__DIR__) . '/app/bootstrap.php';

$method = strtoupper((string) ($_SERVER['REQUEST_METHOD'] ?? 'GET'));
$uri = (string) ($_SERVER['REQUEST_URI'] ?? '/');

if ($method === 'OPTIONS') {
    ApiController::setApiHeaders();
    http_response_code(204);
    exit;
}

$router = new Router();

$router->get('/', 'IndexController@infoAction');
$router->get('/health', 'HealthController@statusAction');
$router->get('/mascotas', 'MascotasController@indexAction');
$router->get('/mascotas/{id}', 'MascotasController@showAction');
$router->post('/auth/login', 'AuthController@loginAction');
$router->post('/adopciones', 'AdopcionesController@storeAction');

$adminMiddleware = [JwtAuthMiddleware::class];
$router->post('/mascotas', 'MascotasController@storeAction', ['middleware' => $adminMiddleware]);
$router->put('/mascotas/{id}', 'MascotasController@updateAction', ['middleware' => $adminMiddleware]);
$router->delete('/mascotas/{id}', 'MascotasController@deleteAction', ['middleware' => $adminMiddleware]);

$route = $router->match($method, $uri);

$request = [
    'method' => $method,
    'uri' => $uri,
    'headers' => get_request_headers(),
    'query' => $_GET,
    'post' => $_POST,
    'body' => json_input(),
    'server' => $_SERVER,
];

$dispatcher = new Dispatcher();
$dispatcher->dispatch($route, $request);

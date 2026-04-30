<?php

declare(strict_types=1);

use App\Core\Router;
use PHPUnit\Framework\TestCase;

final class RouterTest extends TestCase
{
    public function testMatchesGetRouteWithParams(): void
    {
        $router = new Router();
        $router->get('/mascotas/{id}', 'MascotasController@showAction');

        $match = $router->match('GET', '/mascotas/42?foo=bar');

        $this->assertIsArray($match);
        $this->assertSame('MascotasController@showAction', $match['handler']);
        $this->assertSame('42', $match['params']['id']);
    }

    public function testReturnsNullWhenRouteDoesNotExist(): void
    {
        $router = new Router();
        $router->get('/health', 'HealthController@statusAction');

        $match = $router->match('GET', '/no-existe');

        $this->assertNull($match);
    }

    public function testMatchesPutRouteWithParams(): void
    {
        $router = new Router();
        $router->put('/mascotas/{id}', 'MascotasController@updateAction');

        $match = $router->match('PUT', '/mascotas/7');

        $this->assertIsArray($match);
        $this->assertSame('MascotasController@updateAction', $match['handler']);
        $this->assertSame('7', $match['params']['id']);
    }
}

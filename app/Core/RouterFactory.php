<?php

declare(strict_types=1);

namespace App\Core;

use Contributte\ApiRouter\ApiRoute;
use Nette;
use Nette\Application\Routers\RouteList;

final class RouterFactory
{
    use Nette\StaticClass;

    public static function createRouter(): RouteList
    {
        $router = new RouteList();

        $apiPrefix = '/api/v1';

        $router->add(
            new ApiRoute("{$apiPrefix}/auth/register", 'ApiV1:AuthRegister', [
                'methods' => ['POST' => 'post'],
            ])
        );

        $router->add(
            new ApiRoute("{$apiPrefix}/auth/login", 'ApiV1:AuthLogin', [
                'methods' => ['POST' => 'post'],
            ])
        );

        $router->add(
            new ApiRoute("{$apiPrefix}/users", 'ApiV1:UserList', [
                'methods' => ['GET' => 'get'],
            ])
        );

        $router->add(
            new ApiRoute("{$apiPrefix}/users", 'ApiV1:User', [
                'methods' => ['POST' => 'post'],
            ])
        );

        $router->add(
            new ApiRoute("{$apiPrefix}/users/<id>", 'ApiV1:User', [
                'methods' => [
                    'GET' => 'get',
                    'PUT' => 'put',
                    'DELETE' => 'delete',
                ]
            ])
        );

        $router->add(
            new ApiRoute("{$apiPrefix}/articles", 'ApiV1:ArticleList', [
                'methods' => ['GET' => 'get'],
            ])
        );

        $router->add(
            new ApiRoute("{$apiPrefix}/articles", 'ApiV1:Article', [
                'methods' => ['POST' => 'post'],
            ])
        );

        $router->add(
            new ApiRoute("{$apiPrefix}/articles/<id>", 'ApiV1:Article', [
                'methods' => [
                    'GET' => 'get',
                    'PUT' => 'put',
                    'DELETE' => 'delete',
                ],
            ])
        );

        return $router;
    }
}

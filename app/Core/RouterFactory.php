<?php

declare(strict_types=1);

namespace App\Core;

use Nette;
use Nette\Application\Routers\RouteList;

final class RouterFactory
{
    use Nette\StaticClass;

    public static function createRouter(): RouteList
    {
        $router = new RouteList();

        $frontRouter = new RouteList('Front');
        $frontRouter->addRoute('<presenter>/<action>[/<id>]', 'Home:default');

        $apiRouter = self::createApiV1Router();

        $router->add($apiRouter);
        $router->add($frontRouter);

        return $router;
    }

    private static function createApiV1Router(): RouteList
    {
        $apiPrefix = '/api/v1';

        $apiRouter = new RouteList('Api');
        $apiRouter->addRoute("{$apiPrefix}/users", 'V1:UserList:');

        return $apiRouter;
    }
}

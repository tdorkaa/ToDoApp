<?php

namespace ToDoApp;

use PDO;
use Slim\App;
use ToDoApp\Controller\HealthCheck;

class AppBuilder
{
    public static function build()
    {
        $app = new App;
        $container = $app->getContainer();

        $container['pdo'] = function () {
            return (new PdoFactory())->getPDO();
        };

        $container[HealthCheck::class] = function ($container) {
            return new HealthCheck(
                $container['pdo']
            );
        };

        $app->get('/healthcheck', HealthCheck::class . ':healthcheck');

        return $app;
    }
}
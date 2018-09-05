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
            return new PDO('mysql:host=mysql;charset=utf8mb4', 'academy', 'academy', [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]);
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
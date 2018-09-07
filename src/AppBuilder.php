<?php

namespace ToDoApp;

use Slim\App;
use ToDoApp\Controller\HealthCheck;
use ToDoApp\Controller\Todos as TodosController;
use ToDoApp\Controller\Todos;
use ToDoApp\Dao\TodosDao;

class AppBuilder
{
    public static function build()
    {
        $app = new App;
        $container = $app->getContainer();

        self::setUpRoutes($app);
        self::setUpDb($container);
        self::setUpDependencies($container);

        return $app;
    }

    private static function setUpRoutes($app)
    {
        $app->get('/healthcheck', HealthCheck::class . ':healthcheck');
        $app->get('/', TodosController::class . ':actionIndex');
    }

    private static function setUpDb($container)
    {
        $container['pdo'] = function () {
            return (new PdoFactory(new EnvironmentLoader()))->getPDO();
        };
        return $container;
    }

    private static function setUpDependencies($container)
    {
        $container[HealthCheck::class] = function ($container) {
            return new HealthCheck(
                $container['pdo']
            );
        };

        $container[TodosController::class] = function ($container) {
            return new TodosController(
                new TodosDao($container['pdo'])
            );
        };
    }
}
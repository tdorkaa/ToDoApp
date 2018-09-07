<?php

namespace ToDoApp;

use Slim\App;
use Slim\Views\Twig;
use Slim\Views\TwigExtension;
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
        self::setUpTwig($container);
        self::setUpDependencies($container);

        return $app;
    }

    private static function setUpRoutes($app)
    {
        $app->get('/healthcheck', HealthCheck::class . ':healthcheck');
        $app->get('/', TodosController::class . ':actionIndex');
        $app->post('/create/todo', TodosController::class . ':actionAdd');
        $app->post('/set-complete/todo/{id}', TodosController::class . ':actionComplete');
    }

    private static function setUpDb($container)
    {
        $container['pdo'] = function () {
            return (new PdoFactory(new EnvironmentLoader()))->getPDO();
        };
        return $container;
    }

    private static function setUpTwig($container)
    {
        $container['view'] = function ($container) {
            $view = new Twig(__DIR__ . '/../view', [
                'cache' => false
            ]);

            // Instantiate and add Slim specific extension
            $basePath = rtrim(str_ireplace('index.php', '', $container->get('request')->getUri()->getBasePath()), '/');
            $view->addExtension(new TwigExtension($container->get('router'), $basePath));

            return $view;
        };
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
                new TodosDao($container['pdo']),
                $container['view']
            );
        };
    }
}
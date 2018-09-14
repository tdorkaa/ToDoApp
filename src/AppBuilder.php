<?php

namespace ToDoApp;

use Slim\App;
use Slim\Container;
use Slim\Csrf\Guard;
use Slim\Views\Twig;
use Slim\Views\TwigExtension;
use ToDoApp\Controller\HealthCheck;
use ToDoApp\Controller\InsertTodoAction;
use ToDoApp\Controller\Todos as TodosController;
use ToDoApp\Controller\Todos;
use ToDoApp\Controller\UpdateTodoAction;
use ToDoApp\Dao\TodosDao;
use ToDoApp\Sanitizer\InputSanitizer;
use ToDoApp\Validator\InputValidator;

class AppBuilder
{
    public static function build(Container $container = null)
    {
        if(!$container) {
            $container = new Container();
            $container = self::setCsrf($container);
        }

        $app = new App($container);
        $app->add($container->get('csrf'));

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
        $app->post('/update/todo/{id}', UpdateTodoAction::class . ':actionSave');
        $app->post('/create/todo', InsertTodoAction::class . ':actionSave');
        $app->post('/set-complete/todo/{id}', TodosController::class . ':actionComplete');
        $app->post('/delete/todo/{id}', TodosController::class . ':actionDelete');
        $app->get('/update/todo/{id}', TodosController::class . ':actionUpdateIndex');
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
                $container['view'],
                $container['csrf'],
                new TodoFactory()
            );
        };

        $container[InsertTodoAction::class] = function ($container) {
            return new InsertTodoAction(
                new TodosDao($container['pdo']),
                $container['view'],
                new InputValidator(),
                new InputSanitizer()
            );
        };

        $container[UpdateTodoAction::class] = function ($container) {
            return new UpdateTodoAction(
                new TodosDao($container['pdo']),
                $container['view'],
                new InputValidator(),
                new InputSanitizer()
            );
        };
    }

    /**
     * @param Container $container
     * @return Container
     */
    private static function setCsrf(Container $container): Container
    {
        $container['csrf'] = function ($c) {
            return new Guard;
        };
        return $container;
    }
}
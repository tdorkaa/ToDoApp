<?php

namespace ToDoApp;

use Slim\App;
use ToDoApp\Controller\HealthCheck;

class AppBuilder
{
    public static function build()
    {
        $app = new App;

        $app->get('/healthcheck', HealthCheck::class . ':healthcheck');

        return $app;
    }
}
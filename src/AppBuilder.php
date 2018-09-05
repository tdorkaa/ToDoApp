<?php

namespace ToDoApp;

use Slim\App;
use Slim\Http\Request;
use Slim\Http\Response;

class AppBuilder
{
    public static function build()
    {
        $app = new App;

        $app->get('/healthcheck', function (Request $request, Response $response, array $args) {
            $response->getBody()->write("OK");
            return $response;
        });

        return $app;
    }
}
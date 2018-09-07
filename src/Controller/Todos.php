<?php

namespace ToDoApp\Controller;

use Slim\Http\Request;
use Slim\Http\Response;

class Todos
{
    public function actionIndex(Request $request, Response $response, array $args)
    {
        return $response;
    }
}
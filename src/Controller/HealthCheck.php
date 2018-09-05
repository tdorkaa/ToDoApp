<?php

namespace ToDoApp\Controller;

use Slim\Http\Request;
use Slim\Http\Response;

class HealthCheck
{
    public function healthcheck(Request $request, Response $response, array $args)
    {
        $response->getBody()->write("OK");
        return $response;
    }
}
<?php

namespace ToDoApp\Controller;

use Slim\Http\Request;
use Slim\Http\Response;

class HealthCheck
{
    private $pdo;

    public function __construct(\PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    public function healthcheck(Request $request, Response $response, array $args)
    {   $this->pdo->query('SELECT 1');
        $response->getBody()->write("OK");
        return $response;
    }
}
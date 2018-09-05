<?php

namespace Tests;

use PHPUnit\Framework\TestCase;
use Slim\Http\Environment;
use Slim\Http\Request;
use ToDoApp\AppBuilder;

class HealthcheckTest extends TestCase
{
    /**
     * @test
     */
    public function callsHealthcheck_Returns200()
    {
        $app = AppBuilder::build();
        $env = Environment::mock([
            'REQUEST_METHOD' => 'GET',
            'REQUEST_URI'    => '/healthcheck',
        ]);
        $req = Request::createFromEnvironment($env);
        $app->getContainer()['request'] = $req;
        $response = $app->run(true);

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals('OK', (String)$response->getBody());
    }
}
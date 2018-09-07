<?php

namespace Tests\Webtestcase;

use Slim\Http\Environment;
use Slim\Http\Request;
use Slim\Http\Response;
use ToDoApp\AppBuilder;

trait ProcessRequestTrait
{

    private function processRequest($method, $url): Response
    {
        $app = AppBuilder::build();

        $request = Request::createFromEnvironment(
            Environment::mock([
                'REQUEST_METHOD' => $method,
                'REQUEST_URI' => $url
            ])
        );

        return $app->process($request, new Response());
    }

}
<?php

namespace Tests\Webtestcase;

use Slim\Http\Environment;
use Slim\Http\Request;
use Slim\Http\Response;
use ToDoApp\AppBuilder;

trait ProcessRequestTrait
{

    private function processRequest($method, $url, $body = null): Response
    {
        $app = AppBuilder::build();

        $request = Request::createFromEnvironment(
            Environment::mock([
                'REQUEST_METHOD' => $method,
                'REQUEST_URI' => $url
            ])
        );
        if ($body) {
            $request = $request->withParsedBody($body);
        }

        return $app->process($request, new Response());
    }

}
<?php

namespace Tests\Webtestcase;

use Slim\Container;
use Slim\Csrf\Guard;
use Slim\Http\Environment;
use Slim\Http\Request;
use Slim\Http\Response;
use ToDoApp\AppBuilder;

trait ProcessRequestTrait
{

    private function processRequest($method, $url, $body = null, $useMockcsrf = true): Response
    {
        if($useMockcsrf) {
            $container = $this->createContainerWithMockCsrf();
        }

        $app = AppBuilder::build($container);

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

    private function createContainerWithMockCsrf()
    {
        $configuration = [
            'settings' => [
                'displayErrorDetails' => true,
            ],
        ];
        $container = new Container($configuration);

        $mockGuard = $this->getMockBuilder(Guard::class)
            ->setMethods(['validateToken'])
            ->getMock();

        $mockGuard
            ->expects($this->any())
            ->method('validateToken')
            ->willReturn(true);

        $container['csrf'] = function ($c) use ($mockGuard) {
            return $mockGuard;
        };
        return $container;
    }


}
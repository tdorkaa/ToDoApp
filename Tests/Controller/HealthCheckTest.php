<?php

use PHPUnit\Framework\TestCase;
use Slim\Http\Request;
use Slim\Http\Response;
use ToDoApp\Controller\HealthCheck;

class HealthCheckTest extends TestCase
{
    /**
     * @test
     */
    public function healthcheck_makeAQuery()
    {
        $mockPDO = $this->createMock(PDO::class);
        $mockPDO->expects($this->once())
            ->method('query')
            ->with('SELECT 1');

        $healthCheck = new HealthCheck($mockPDO);
        $healthCheck->healthcheck($this->createMock(Request::class),
                                  new Response(), []);
    }
}
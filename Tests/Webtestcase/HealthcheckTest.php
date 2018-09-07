<?php

namespace Tests\Webtestcase;

use PHPUnit\Framework\TestCase;

class HealthcheckTest extends TestCase
{

    use ProcessRequestTrait;

    /**
     * @test
     */
    public function callsHealthcheck_Returns200()
    {
        $response = $this->processRequest('GET', '/healthcheck');

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals('OK', (String)$response->getBody());
    }
}
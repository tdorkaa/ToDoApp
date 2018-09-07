<?php

namespace Tests\Webtestcase;

use PHPUnit\Framework\TestCase;

class TodosControllerTest extends TestCase
{

    use ProcessRequestTrait;

    /**
     * @test
     */
    public function actionIndex_returnsStatus200()
    {
        $response = $this->processRequest('GET', '/');

        $this->assertEquals(200, $response->getStatusCode());
    }
}
<?php

namespace Tests\Webtestcase;

use PHPUnit\Framework\TestCase;
use Tests\DbHelperTrait;

class CsrfTest extends TestCase
{
    use ProcessRequestTrait;
    use DbHelperTrait;

    protected function setUp()
    {
        $this->createPDO();
        $this->truncate('todos');
    }

    /**
     * @test
     * @dataProvider provideEndPoints
     */
    public function actionComplete_Returns400IfTokenNotGenerated($url)
    {
        $response = $this->processRequest('POST', $url, null, false);
        $this->assertEquals(400, $response->getStatusCode());
    }

    public function provideEndPoints()
    {
        return [
            ['/set-complete/todo/2'],
            ['/delete/todo/2'],
            ['/update/todo/2'],
            ['/create/todo']
        ];
    }
}
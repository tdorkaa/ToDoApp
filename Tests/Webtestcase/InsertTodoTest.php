<?php

namespace Tests\Webtestcase;

use PHPUnit\Framework\TestCase;
use Tests\DbHelperTrait;

class InsertTodoTest extends TestCase
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
     */
    public function actionAdd_InsertsTodoToDb()
    {
        $body = ['name' => 'todo name1', 'description' => 'todo description', 'due_at' => '2018-08-29 10:00:00'];
        $response = $this->processRequest('POST', '/create/todo', $body);
        $actual = $this->list('todos');
        $this->assertEquals(301, $response->getStatusCode());
        $this->assertEquals([
            'id' => 1,
            'name' => 'todo name1',
            'description' => 'todo description',
            'status' => 'incomplete',
            'due_at' => '2018-08-29 10:00:00'
        ], $actual[0]);
    }

    /**
     * @test
     */
    public function actionAdd_GivenEmptyData_DoesNotInsertAndSendErrorsInUrl()
    {
        $body = ['name' => '', 'description' => '', 'due_at' => ''];
        $response = $this->processRequest('POST', '/create/todo', $body);
        $actual = $this->list('todos');
        $this->assertEquals(301, $response->getStatusCode());
        $this->assertEquals('/?errors=Name is missing., Description is missing., Due date is missing.', $response->getHeaderLine('Location'));
        $this->assertEquals([], $actual);
    }
}
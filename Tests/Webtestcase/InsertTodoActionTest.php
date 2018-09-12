<?php

namespace Tests\Webtestcase;

use PHPUnit\Framework\TestCase;
use Tests\DbHelperTrait;
use ToDoApp\Validator\InputValidator;

class InsertTodoActionTest extends TestCase
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

        $emptyNameCode = InputValidator::ERROR_EMPTY_NAME;
        $emptyDescriptionCode = InputValidator::ERROR_EMPTY_DESCRIPTION;
        $emptyDueAt = InputValidator::ERROR_EMPTY_DUE_AT;
        $this->assertEquals("/?errors[]={$emptyNameCode}&errors[]={$emptyDescriptionCode}&errors[]={$emptyDueAt}", $response->getHeaderLine('Location'));
        $this->assertEquals([], $actual);
    }
}
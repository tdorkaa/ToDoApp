<?php

namespace Tests\Webtestcase;

use PHPUnit\Framework\TestCase;
use Tests\DbHelperTrait;
use Tests\TodoForTest;
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
        $body = ['name' => 'todo name1', 'description' => 'todo description1', 'due_at' => '2018-08-29 10:00:00', 'csrf_name' => 'a', 'csrf_value' => 'a'];
        $response = $this->processRequest('POST', '/create/todo', $body);
        $actual = $this->list('todos');
        $this->assertEquals(301, $response->getStatusCode());
        $this->assertEquals(TodoForTest::$todo1, $actual[0]);
    }

    /**
     * @test
     */
    public function actionAdd_GivenEmptyData_DoesNotInsertAndSendErrorsInUrl()
    {
        $body = ['name' => '', 'description' => '', 'due_at' => '', 'csrf_name' => 'a', 'csrf_value' => 'a'];
        $response = $this->processRequest('POST', '/create/todo', $body);
        $actual = $this->list('todos');
        $this->assertEquals(301, $response->getStatusCode());

        $emptyNameCode = InputValidator::ERROR_EMPTY_NAME;
        $emptyDescriptionCode = InputValidator::ERROR_EMPTY_DESCRIPTION;
        $emptyDueAt = InputValidator::ERROR_EMPTY_DUE_AT;
        $this->assertEquals("/?errors[]={$emptyNameCode}&errors[]={$emptyDescriptionCode}&errors[]={$emptyDueAt}", $response->getHeaderLine('Location'));
        $this->assertEquals([], $actual);
    }

    /**
     * @test
     */
    public function actionAdd_GivenDueAtInvalid_DoesNotInsertAndSendErrorsInUrl()
    {
        $body = ['name' => 'todo name', 'description' => 'todo description', 'due_at' => 'invalid due date', 'csrf_name' => 'a', 'csrf_value' => 'a'];
        $response = $this->processRequest('POST', '/create/todo', $body);
        $actual = $this->list('todos');
        $this->assertEquals(301, $response->getStatusCode());
        $invalidDueDate = InputValidator::ERROR_INVALID_DUE_AT;
        $this->assertEquals("/?errors[]={$invalidDueDate}", $response->getHeaderLine('Location'));
        $this->assertEquals([], $actual);
    }

    /**
     * @test
     */
    public function actionAdd_GivenInputsAreNotClean_InsertSanitizedTodo()
    {
        $body = ['name' => '<br>todo name1', 'description' => '       todo description1', 'due_at' => '2018-08-29 10:00:00         ', 'csrf_name' => 'a', 'csrf_value' => 'a'];
        $response = $this->processRequest('POST', '/create/todo', $body);
        $actual = $this->list('todos');
        $this->assertEquals(301, $response->getStatusCode());
        $this->assertEquals(TodoForTest::$todo1, $actual[0]);
    }
}
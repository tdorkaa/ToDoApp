<?php

namespace Tests\Webtestcase;

use PHPUnit\Framework\TestCase;
use Tests\DbHelperTrait;
use ToDoApp\Entity\Status;
use ToDoApp\Entity\Todo;

class TodosControllerTest extends TestCase
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
    public function actionIndex_returnsStatus200()
    {
        $response = $this->processRequest('GET', '/');

        $this->assertEquals(200, $response->getStatusCode());
    }

    /**
     * @test
     */
    public function actionIndex_returnsTodosFromDb()
    {
        $todos = [
            new Todo(1, 'todo name1', 'todo description1', '2018-08-29 10:00:00'),
            new Todo(2, 'todo name2', 'todo description1', '2018-08-29 10:00:00'),
            new Todo(3, 'todo name3', 'todo description1', '2018-08-29 10:00:00'),
        ];
        $this->createTodos($todos);
        $response = $this->processRequest('GET', '/');

        $this->assertContains('todo name1', (string)$response->getBody());
        $this->assertContains('todo name2', (string)$response->getBody());
        $this->assertContains('todo name3', (string)$response->getBody());
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

    /**
     * @test
     */
    public function actionComplete_SetsTodoAsCompleted()
    {
        $todos = [
            new Todo(1, 'todo name1', 'todo description1', '2018-08-29 10:00:00'),
            new Todo(2, 'todo name2', 'todo description1', '2018-08-29 10:00:00'),
            new Todo(3, 'todo name3', 'todo description1', '2018-08-29 10:00:00'),
        ];
        $this->createTodos($todos);
        $response = $this->processRequest('POST', '/set-complete/todo/2');
        $actual = $this->listTodos();
        $this->assertEquals(301, $response->getStatusCode());
        $this->assertEquals(Status::INCOMPLETE, $actual[0]->getStatus());
        $this->assertEquals(Status::COMPLETE,   $actual[1]->getStatus());
        $this->assertEquals(Status::INCOMPLETE, $actual[2]->getStatus());
    }

    /**
     * @test
     */
    public function actionDelete_DeletesTodo()
    {
        $todos = [
            new Todo(1, 'todo name1', 'todo description1', '2018-08-29 10:00:00'),
            new Todo(2, 'todo name2', 'todo description1', '2018-08-29 10:00:00'),
            new Todo(3, 'todo name3', 'todo description1', '2018-08-29 10:00:00'),
        ];
        $this->createTodos($todos);
        $response = $this->processRequest('POST', '/delete/todo/2');
        $actual = $this->listTodos();
        $this->assertEquals(301, $response->getStatusCode());
        $this->assertEquals(2, count($actual));
        $this->assertEquals($todos[0]->getId(), $actual[0]->getId());
        $this->assertEquals($todos[2]->getId(), $actual[1]->getId());
    }

    /**
     * @test
     */
    public function actionUpdate_UpdatesTodo()
    {
        $todos = [
            new Todo(1, 'todo name1', 'todo description1', '2018-08-29 10:00:00'),
            new Todo(2, 'todo name2', 'todo description1', '2018-08-29 10:00:00'),
            new Todo(3, 'todo name3', 'todo description1', '2018-08-29 10:00:00'),
        ];
        $this->createTodos($todos);
        $requestBody = [
            'name' => 'todo name4',
            'description' => 'todo description4',
            'due_at' => '2018-08-30 10:00:00'
        ];
        $response = $this->processRequest('POST', '/update/todo/2', $requestBody);
        $actual = $this->listTodos();
        $this->assertEquals(301, $response->getStatusCode());
        $this->assertEquals([
            $todos[0],
            new Todo(2, $requestBody['name'], $requestBody['description'], $requestBody['due_at']),
            $todos[2],
        ], $actual);
    }

    /**
     * @test
     */
    public function actionUpdate_GivenNameIsEmtpy_SendErrorsInUrlAndDoesNotUpdateTodo()
    {
        $todos = [
            new Todo(1, 'todo name1', 'todo description1', '2018-08-29 10:00:00'),
            new Todo(2, 'todo name2', 'todo description1', '2018-08-29 10:00:00'),
            new Todo(3, 'todo name3', 'todo description1', '2018-08-29 10:00:00'),
        ];
        $this->createTodos($todos);
        $requestBody = [
            'name' => '',
            'description' => 'todo description4',
            'due_at' => '2018-08-30 10:00:00'
        ];
        $response = $this->processRequest('POST', '/update/todo/2', $requestBody);
        $actual = $this->listTodos();
        $this->assertEquals(301, $response->getStatusCode());
        $this->assertEquals('/?errors=Name is missing.', $response->getHeaderLine('Location'));

        $this->assertEquals($todos, $actual);
    }

    /**
     * @test
     */
    public function actionUpdateIndex_ReturnsTodoUpdatePage()
    {
        $todos = [
            new Todo(1, 'todo name1', 'todo description1', '2018-08-29 10:00:00')
        ];
        $this->createTodos($todos);
        $response = $this->processRequest('GET', '/update/todo/1');
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertContains($todos[0]->getName(), (string)$response->getBody());
        $this->assertContains($todos[0]->getDescription(), (string)$response->getBody());
        $this->assertContains($todos[0]->getDueAt(), (string)$response->getBody());
    }
}
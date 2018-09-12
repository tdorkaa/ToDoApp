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
    public function actionIndex_GivenUrlContainsErrorCodes_DisplayErrorMessage()
    {
        $response = $this->processRequest('GET', '?errors[]=empty_name&errors[]=empty_description&errors[]=empty_due_at');

        $this->assertContains('Please fill the name field out.', (string)$response->getBody());
        $this->assertContains('Please fill the description field out.', (string)$response->getBody());
        $this->assertContains('Please fill the due at field out.', (string)$response->getBody());
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
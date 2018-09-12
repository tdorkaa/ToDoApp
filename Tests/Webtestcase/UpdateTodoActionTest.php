<?php

namespace Tests\Webtestcase;

use PHPUnit\Framework\TestCase;
use Tests\DbHelperTrait;
use ToDoApp\Entity\Todo;

class UpdateTodoActionTest extends TestCase
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
//
//    /**
//     * @test
//     */
//    public function actionUpdate_GivenNameIsEmtpy_SendErrorsInUrlAndDoesNotUpdateTodo()
//    {
//        $todos = [
//            new Todo(1, 'todo name1', 'todo description1', '2018-08-29 10:00:00'),
//            new Todo(2, 'todo name2', 'todo description1', '2018-08-29 10:00:00'),
//            new Todo(3, 'todo name3', 'todo description1', '2018-08-29 10:00:00'),
//        ];
//        $this->createTodos($todos);
//        $requestBody = [
//            'name' => '',
//            'description' => 'todo description4',
//            'due_at' => '2018-08-30 10:00:00'
//        ];
//        $response = $this->processRequest('POST', '/update/todo/2', $requestBody);
//        $actual = $this->listTodos();
//        $this->assertEquals(301, $response->getStatusCode());
//        $this->assertEquals('/?errors[]=Name is missing.&errors[]=Description is missing.&errors[]=Due date is missing.', $response->getHeaderLine('Location'));
//        $this->assertEquals($todos, $actual);
//    }

}
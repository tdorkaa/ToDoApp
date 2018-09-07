<?php

namespace Tests\Webtestcase;

use PHPUnit\Framework\TestCase;
use Tests\DbHelperTrait;
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
}
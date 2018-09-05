<?php

use PHPUnit\Framework\TestCase;

class TodosDaoTest extends TestCase
{
    /**
     * @test
     */
    public function listTodos_GivenOneTodoInTheDb_ReturnsTodo()
    {
        $todo = [
            'name' => 'todo name',
            'description' => 'todo description',
            'status' => 'complete',
            'due_at' => '2018-08-29 10:00:00'
        ];
        $this->markTestIncomplete();
    }
}
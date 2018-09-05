<?php

use PHPUnit\Framework\TestCase;
use ToDoApp\Dao\TodosDao;
use ToDoApp\EnvironmentLoader;
use ToDoApp\PdoFactory;

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
            'status' => 'incomplete',
            'due_at' => '2018-08-29 10:00:00'
        ];
        $PDO = (new PdoFactory(new EnvironmentLoader()))->getPDO();
        $PDO->query('TRUNCATE TABLE todos');

        $sql = '
            INSERT INTO todos (
                name, 
                description, 
                status, 
                due_at
            )
            VALUES (
                :name, 
                :description, 
                :status, 
                :due_at
            )
        ';
        $statement = $PDO->prepare($sql);
        $statement->execute(array(
            ':name'               => $todo['name'],
            ':description'        => $todo['description'],
            ':status'             => $todo['status'],
            ':due_at'             => $todo['due_at'],
        ));

        $todosDao = new TodosDao($PDO);
        $result = $todosDao->listTodos();
        $this->assertEquals($todo, $result[0]);
    }
}
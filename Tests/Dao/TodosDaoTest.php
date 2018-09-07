<?php

use PHPUnit\Framework\TestCase;
use ToDoApp\Dao\TodosDao;
use ToDoApp\EnvironmentLoader;
use ToDoApp\PdoFactory;
use ToDoApp\Entity\Todo;

class TodosDaoTest extends TestCase
{
    /**
     * @test
     */
    public function listTodos_GivenOneTodoInTheDb_ReturnsTodo()
    {
        $todo = new Todo('todo name', 'todo description', '2018-08-29 10:00:00');
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
            ':name' => $todo->getName(),
            ':description' => $todo->getDescription(),
            ':status' => $todo->getStatus(),
            ':due_at' => $todo->getDueAt()
        ));

        $todosDao = new TodosDao($PDO);
        $result = $todosDao->listTodos();
        $this->assertEquals($todo, $result[0]);
    }
}
<?php

use PHPUnit\Framework\TestCase;
use ToDoApp\Dao\TodosDao;
use ToDoApp\EnvironmentLoader;
use ToDoApp\PdoFactory;
use ToDoApp\Entity\Todo;

class TodosDaoTest extends TestCase
{
    /**
     * @var PDO
     */
    private $PDO;
    protected function setUp()
    {
        $this->PDO = (new PdoFactory(new EnvironmentLoader()))->getPDO();
        $this->PDO->query('TRUNCATE TABLE todos');    }

    /**
     * @test
     */
    public function listTodos_GivenOneTodoInTheDb_ReturnsTodo()
    {
        $todo = new Todo('todo name', 'todo description', '2018-08-29 10:00:00');
        $this->createTodos([$todo]);

        $todosDao = new TodosDao($this->PDO);
        $result = $todosDao->listTodos();
        $this->assertEquals($todo, $result[0]);
    }

    private function createTodos($todos)
    {
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
        $statement = $this->PDO->prepare($sql);
        foreach ($todos as $todo) {
            $statement->execute(array(
                ':name' => $todo->getName(),
                ':description' => $todo->getDescription(),
                ':status' => $todo->getStatus(),
                ':due_at' => $todo->getDueAt()
            ));
        }
    }
}
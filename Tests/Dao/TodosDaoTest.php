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
    /**
     * @var TodosDao
     */
    private $todosDao;

    protected function setUp()
    {
        $this->PDO = (new PdoFactory(new EnvironmentLoader()))->getPDO();
        $this->PDO->query('TRUNCATE TABLE todos');
        $this->todosDao = new TodosDao($this->PDO);
    }

    /**
     * @test
     */
    public function listTodos_GivenOneTodoInTheDb_ReturnsTodo()
    {
        $todo = new Todo(1, 'todo name', 'todo description', '2018-08-29 10:00:00');
        $this->createTodos([$todo]);
        $result = $this->todosDao->listTodos();
        $this->assertEquals($todo, $result[0]);
    }

    /**
     * @test
     */
    public function listTodos_GivenMultipleTodosInDb_ReturnsTodosOrderedByImportance()
    {

        $todos = [
            new Todo(1, 'todo name1', 'todo description1', '2018-08-29 10:00:00'),
            new Todo(2, 'todo name2', 'todo description2', '2018-08-30 10:00:00'),
            new Todo(3, 'todo name3', 'todo description3', '2018-08-27 10:00:00')
        ];
        $this->createTodos($todos);
        $result = $this->todosDao->listTodos();
        $this->assertEquals($todos[0], $result[1]);
        $this->assertEquals($todos[1], $result[2]);
        $this->assertEquals($todos[2], $result[0]);
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
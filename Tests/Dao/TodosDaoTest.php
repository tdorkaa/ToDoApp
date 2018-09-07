<?php

use PHPUnit\Framework\TestCase;
use ToDoApp\Dao\TodosDao;
use ToDoApp\Entity\Status;
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

    /**
     * @test
     */
    public function listTodos_SomeCompletedTodods_ReturnsOnlyIncompleteTodos()
    {

        $todos = [
            new Todo(1, 'todo name1', 'todo description1', '2018-08-30 10:00:00', Status::COMPLETE),
            new Todo(2, 'todo name2', 'todo description2', '2018-08-30 10:00:00'),
            new Todo(3, 'todo name3', 'todo description3', '2018-08-30 10:00:00')
        ];
        $this->createTodos($todos);
        $result = $this->todosDao->listTodos();
        $this->assertEquals([
            $todos[1],
            $todos[2]
        ], $result);
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

    /**
     * @test
     */
    public function addTodo_GivenTodo_AddsTodoToDb()
    {
        $todo = new Todo(null, 'todo name1', 'todo description1', '2018-08-29 10:00:00');
        $this->todosDao->addTodo($todo);
        $actual = $this->todosDao->listTodos()[0];
        $this->assertEquals($todo->getName(), $actual->getName());
        $this->assertEquals($todo->getDescription(), $actual->getDescription());
        $this->assertEquals($todo->getDueAt(), $actual->getDueAt());
        $this->assertEquals($todo->getStatus(), $actual->getStatus());
    }

    /**
     * @test
     */
    public function updateTodo_GivenExistingTodo_updatesTodoWithNewFields()
    {
        $todo = new Todo(null, 'todo name1', 'todo description1', '2018-08-29 10:00:00');
        $this->todosDao->addTodo($todo);
        $updatedTodo = new Todo(1, 'updated name1', 'updated description1', '2018-08-30 10:00:00');
        $this->todosDao->updateTodo($updatedTodo);
        $actualTodo = $this->todosDao->listTodos()[0];
        $this->assertEquals($updatedTodo, $actualTodo);
    }

    /**
     * @test
     */
    public function updateTodo_GivenExistingTodo_updatesOnlyThatTodo()
    {
        $todos = [
            new Todo(1, 'todo name1', 'todo description1', '2018-08-29 10:00:00'),
            new Todo(2, 'todo name1', 'todo description1', '2018-08-29 10:00:00'),
            new Todo(3, 'todo name1', 'todo description1', '2018-08-29 10:00:00'),
        ];
        $this->createTodos($todos);
        $updatedTodo = new Todo(2, 'updated name1', 'updated description1', '2018-08-29 10:00:00');
        $this->todosDao->updateTodo($updatedTodo);
        $actualTodo = $this->todosDao->listTodos();
        $this->assertEquals([
            $todos[0],
            $updatedTodo,
            $todos[2]
        ], $actualTodo);
    }

    /**
     * @test
     */
    public function deleteTodo_GivenExistingTodos_deletesTodoFromDb()
    {
        $todos = [
            new Todo(1, 'todo name1', 'todo description1', '2018-08-29 10:00:00'),
            new Todo(2, 'todo name1', 'todo description1', '2018-08-29 10:00:00'),
            new Todo(3, 'todo name1', 'todo description1', '2018-08-29 10:00:00'),
        ];
        $this->createTodos($todos);
        $this->todosDao->deleteTodo(2);
        $actualTodo = $this->todosDao->listTodos();
        $this->assertEquals([
            $todos[0],
            $todos[2]
        ], $actualTodo);
    }
}
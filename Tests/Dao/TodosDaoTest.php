<?php

use PHPUnit\Framework\TestCase;
use Tests\DbHelperTrait;
use ToDoApp\Dao\TodosDao;
use ToDoApp\Entity\Status;
use ToDoApp\Entity\Todo;

class TodosDaoTest extends TestCase
{
    use DbHelperTrait;
    /**
     * @var TodosDao
     */
    private $todosDao;

    protected function setUp()
    {
        $this->createPDO();
        $this->truncate('todos');
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
        $this->assertEquals([
                                'id' => 1,
                                'name' => 'todo name',
                                'description' => 'todo description',
                                'due_at' => '2018-08-29 10:00:00',
                                'status' => 'incomplete'
        ], $result[0]);
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
        $this->assertEquals([
            'id' => 1,
            'name' => 'todo name1',
            'description' => 'todo description1',
            'due_at' => '2018-08-29 10:00:00',
            'status' => 'incomplete'
        ], $result[1]);
        $this->assertEquals([
            'id' => 2,
            'name' => 'todo name2',
            'description' => 'todo description2',
            'due_at' => '2018-08-30 10:00:00',
            'status' => 'incomplete'
        ], $result[2]);
        $this->assertEquals([
            'id' => 3,
            'name' => 'todo name3',
            'description' => 'todo description3',
            'due_at' => '2018-08-27 10:00:00',
            'status' => 'incomplete'
        ], $result[0]);
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
        $this->assertEquals(
        [
            [
                'id' => 2,
                'name' => 'todo name2',
                'description' => 'todo description2',
                'due_at' => '2018-08-30 10:00:00',
                'status' => 'incomplete'
            ],
            [
                'id' => 3,
                'name' => 'todo name3',
                'description' => 'todo description3',
                'due_at' => '2018-08-30 10:00:00',
                'status' => 'incomplete'
            ]
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
        $this->assertEquals($todo->getName(), $actual['name']);
        $this->assertEquals($todo->getDescription(), $actual['description']);
        $this->assertEquals($todo->getDueAt(), $actual['due_at']);
        $this->assertEquals($todo->getStatus(), $actual['status']);
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
        $this->assertEquals([
            'id' => 1,
            'name' => 'updated name1',
            'description' => 'updated description1',
            'due_at' => '2018-08-30 10:00:00',
            'status' => 'incomplete'
        ], $actualTodo);
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
        $this->assertEquals(
            [
                [
                    'id' => 1,
                    'name' => 'todo name1',
                    'description' => 'todo description1',
                    'status' => 'incomplete',
                    'due_at' => '2018-08-29 10:00:00'
                ],
                [
                    'id' => 2,
                    'name' => 'updated name1',
                    'description' => 'updated description1',
                    'status' => 'incomplete',
                    'due_at' => '2018-08-29 10:00:00'
                ],
                [
                    'id' => 3,
                    'name' => 'todo name1',
                    'description' => 'todo description1',
                    'status' => 'incomplete',
                    'due_at' => '2018-08-29 10:00:00'
                ]
            ]
        , $actualTodo);
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
            [
                'id' => 1,
                'name' => 'todo name1',
                'description' => 'todo description1',
                'status' => 'incomplete',
                'due_at' => '2018-08-29 10:00:00'
            ],
            [
                'id' => 3,
                'name' => 'todo name1',
                'description' => 'todo description1',
                'status' => 'incomplete',
                'due_at' => '2018-08-29 10:00:00'
            ]
        ], $actualTodo);
    }

    /**
     * @test
     */
    public function setComplete_GivenExistingTodo_completeTodo()
    {
        $todos = [
            new Todo(1, 'todo name1', 'todo description1', '2018-08-29 10:00:00'),
            new Todo(2, 'todo name1', 'todo description1', '2018-08-29 10:00:00'),
            new Todo(3, 'todo name1', 'todo description1', '2018-08-29 10:00:00'),
        ];
        $this->createTodos($todos);
        $this->todosDao->setComplete(2);
        $actualStatuses = $this->PDO->query('SELECT status from todos')->fetchAll(PDO::FETCH_ASSOC);
        $this->assertEquals([
            ['status' => Status::INCOMPLETE],
            ['status' => Status::COMPLETE],
            ['status' => Status::INCOMPLETE]
        ], $actualStatuses);
    }

    /**
     * @test
     */
    public function findById_GivenExistingTodoId_ReturnsThatTodo()
    {
        $todos = [
            new Todo(1, 'todo name1', 'todo description1', '2018-08-29 10:00:00'),
            new Todo(2, 'todo name1', 'todo description1', '2018-08-29 10:00:00'),
            new Todo(3, 'todo name1', 'todo description1', '2018-08-29 10:00:00'),
        ];
        $this->createTodos($todos);
        $actual = $this->todosDao->findById(2);
        $this->assertEquals($todos[1], $actual);
    }
}
<?php

namespace ToDoApp\Dao;


use ToDoApp\Entity\Todo;

class TodosDao
{

    private $PDO;

    public function __construct(\PDO $PDO)
    {
        $this->PDO = $PDO;
    }

    /**
     * @return Todo[]
     */
    public function listTodos()
    {
        $todos = $this->fetchTodos();

        foreach ($todos as $index => $todo) {
            $todos[$index] = Todo::fromArray($todo);
        }

        return $todos;
    }

    private function fetchTodos()
    {
        $sql = '
            SELECT id, name, description, status, due_at 
            FROM todos 
            ORDER BY due_at ASC
        ';
        $statement = $this->PDO->query($sql);
        return $statement->fetchAll(\PDO::FETCH_ASSOC);
    }

    public function addTodo(Todo $todo)
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
        $statement->execute(array(
            ':name' => $todo->getName(),
            ':description' => $todo->getDescription(),
            ':status' => $todo->getStatus(),
            ':due_at' => $todo->getDueAt()
        ));
    }

    public function updateTodo(Todo $todo)
    {
        $sql = '
           UPDATE todos SET
                name=:name, 
                description=:description, 
                status=:status, 
                due_at=:due_at
            WHERE id=:id
        ';
        $statement = $this->PDO->prepare($sql);
        $statement->execute(array(
            ':id' => $todo->getId(),
            ':name' => $todo->getName(),
            ':description' => $todo->getDescription(),
            ':status' => $todo->getStatus(),
            ':due_at' => $todo->getDueAt()
        ));
    }

    public function deleteTodo($id)
    {
        $sql = '
           DELETE FROM todos
            WHERE id=:id
        ';
        $statement = $this->PDO->prepare($sql);
        $statement->execute(array(
            ':id' => $id
        ));
    }
}
<?php

namespace ToDoApp\Dao;


use ToDoApp\Entity\Status;
use ToDoApp\Entity\Todo;

class TodosDao
{

    private $PDO;

    public function __construct(\PDO $PDO)
    {
        $this->PDO = $PDO;
    }

    public function listTodos()
    {
        $statusIncomplete = Status::INCOMPLETE;
        $statement = $this->PDO->prepare("SELECT id, name, description, status, due_at 
            FROM todos 
            WHERE status=:status
            ORDER BY due_at ASC"
        );
        $statement->execute([':status' => $statusIncomplete]);
        return $statement->fetchAll(\PDO::FETCH_ASSOC);
    }

    public function addTodo(Todo $todo)
    {
        $this->PDO->prepare('
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
        ')->execute(array(
            ':name' => $todo->getName(),
            ':description' => $todo->getDescription(),
            ':status' => $todo->getStatus(),
            ':due_at' => $todo->getDueAt()
        ));
    }

    public function updateTodo(Todo $todo)
    {
        $this->PDO->prepare('
           UPDATE todos SET
                name=:name, 
                description=:description, 
                status=:status, 
                due_at=:due_at
           WHERE id=:id
        ')->execute([
            ':id' => $todo->getId(),
            ':name' => $todo->getName(),
            ':description' => $todo->getDescription(),
            ':status' => $todo->getStatus(),
            ':due_at' => $todo->getDueAt()
        ]);
    }

    public function deleteTodo($id)
    {
        $this->PDO->prepare('
           DELETE FROM todos
           WHERE id=:id
        ')->execute([
            ':id' => $id
        ]);
    }

    public function setComplete($id)
    {
        $this->PDO->prepare('
           UPDATE todos SET
                status=:status
           WHERE id=:id
        ')->execute([
            ':id' => $id,
            ':status' => Status::COMPLETE
        ]);
    }

    public function findById($id)
    {
        $statement = $this->PDO->prepare('
            SELECT id, name, description, status, due_at 
            FROM todos
            WHERE id=:id
        ');

        $statement->execute([
            'id' => $id
        ]);

        return Todo::fromArray($statement->fetch(\PDO::FETCH_ASSOC));
    }
}
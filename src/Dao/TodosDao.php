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
        $statusIncomplete = Status::INCOMPLETE;
        return $this->PDO->query("SELECT id, name, description, status, due_at 
            FROM todos 
            WHERE status = '${statusIncomplete}'
            ORDER BY due_at ASC"
        )->fetchAll(\PDO::FETCH_ASSOC);
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
}
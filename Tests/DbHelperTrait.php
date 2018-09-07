<?php

namespace Tests;

use ToDoApp\Entity\Todo;
use ToDoApp\EnvironmentLoader;
use ToDoApp\PdoFactory;

/**
 * Trait DbHelperTrait
 * @package Tests
 */
trait DbHelperTrait
{
    /**
     * @var \PDO
     */
    private $PDO;

    private function createPDO()
    {
        $this->PDO = (new PdoFactory(new EnvironmentLoader()))->getPDO();
    }

    private function truncate($table)
    {
        $this->PDO->query('TRUNCATE TABLE ' . $table );
    }

    /**
     * @param Todo[] $todos
     */
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
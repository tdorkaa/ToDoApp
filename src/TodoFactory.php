<?php

namespace ToDoApp;

use ToDoApp\Entity\Todo;

class TodoFactory
{

    public function build(array $todo)
    {
        return new Todo($todo['id'], $todo['name'], $todo['description'], $todo['due_at'], $todo['status']);
    }

    public function buildList(array $todos)
    {
        $todoFactory = new TodoFactory();
        foreach ($todos as $index => $element) {
            $todos[$index] = $todoFactory->build($element);
        }

        return $todos;
    }
}
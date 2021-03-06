<?php

namespace ToDoApp;

use ToDoApp\Entity\Todo;

class ConverterToTodo
{

    public function build(array $todo)
    {
        return new Todo($todo['id'], $todo['name'], $todo['description'], $todo['due_at'], $todo['status']);
    }

    public function buildList(array $todos)
    {
        $converterToTodo = new ConverterToTodo();
        foreach ($todos as $index => $element) {
            $todos[$index] = $converterToTodo->build($element);
        }

        return $todos;
    }
}
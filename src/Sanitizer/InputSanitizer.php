<?php

namespace ToDoApp\Sanitizer;

use ToDoApp\Entity\Todo;

class InputSanitizer
{

    public function sanitize(Todo $todo)
    {
        $sanitizedTodo = clone $todo;
        $sanitizedTodo->setName(filter_var(trim($sanitizedTodo->getName()), FILTER_SANITIZE_STRING));
        $sanitizedTodo->setDescription(filter_var(trim($sanitizedTodo->getDescription()), FILTER_SANITIZE_STRING));
        $sanitizedTodo->setDueAt(filter_var(trim($sanitizedTodo->getDueAt()), FILTER_SANITIZE_STRING));

        return $sanitizedTodo;
    }
}
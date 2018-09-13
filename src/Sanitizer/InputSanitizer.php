<?php

namespace ToDoApp\Sanitizer;

use ToDoApp\Entity\Todo;

class InputSanitizer
{

    public function sanitize(Todo $todo)
    {
        $todo->setName(trim($todo->getName()));
        $todo->setName(filter_var($todo->getName(), FILTER_SANITIZE_STRING));

        $todo->setDescription(trim($todo->getDescription()));
        $todo->setDescription(filter_var($todo->getDescription(), FILTER_SANITIZE_STRING));

        $todo->setDueAt(trim($todo->getDueAt()));
        $todo->setDueAt(filter_var($todo->getDueAt(), FILTER_SANITIZE_STRING));

        return $todo;
    }
}
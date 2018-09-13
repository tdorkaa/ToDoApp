<?php

namespace ToDoApp\Sanitizer;

use ToDoApp\Entity\Todo;

class InputSanitizer
{

    public function sanitize(Todo $todo)
    {
        $todo->setName(trim($todo->getName()));
        $todo->setDescription(trim($todo->getDescription()));
        return $todo;
    }
}
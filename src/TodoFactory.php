<?php

namespace ToDoApp;

use ToDoApp\Entity\Todo;

class TodoFactory
{

    public function build(array $arrayToBuild)
    {
        return new Todo($arrayToBuild['id'], $arrayToBuild['name'], $arrayToBuild['description'], $arrayToBuild['due_at'], $arrayToBuild['status']);
    }
}
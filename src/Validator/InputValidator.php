<?php

namespace ToDoApp\Validator;

use ToDoApp\Entity\Todo;
use ToDoApp\Exception\InvalidInputException;

class InputValidator
{

    /**
     * @param Todo $todo
     * @throws InvalidInputException
     */
    public function validate(Todo $todo)
    {
        $errorMessage = [];
        if (empty($todo->getName())) {
            array_push($errorMessage, 'Name is missing.');
        }
        if (empty($todo->getDescription())) {
            array_push($errorMessage, 'Description is missing.');
        }
        if (empty($todo->getDueAt())) {
            array_push($errorMessage, 'Due date is missing.');
        }
        if (!empty($errorMessage)) {
            throw new InvalidInputException(implode(', ', $errorMessage));
        }
    }
}
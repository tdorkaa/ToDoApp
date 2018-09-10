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
        foreach (array_slice($todo->getTodoAsAnAssocArray(), 0, 4) as $key => $value) {
            if(empty($value)) {
                array_push($errorMessage, ucfirst($key) . ' is missing.');
            }
        }
        var_dump($errorMessage);
        if(!empty($errorMessage)) {
            throw new InvalidInputException(implode(', ', $errorMessage));
        }
    }
}
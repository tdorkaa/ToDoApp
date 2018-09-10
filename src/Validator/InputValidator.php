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
        foreach ($todo->getTodoAsAnAssocArray() as $key => $value) {
            if(empty($value)) {
                throw new InvalidInputException(ucfirst($key) . ' is missing.');
            }
        }
    }
}
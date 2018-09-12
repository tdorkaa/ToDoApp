<?php

namespace ToDoApp\Validator;

use ToDoApp\Entity\Todo;
use ToDoApp\Exception\InvalidInputException;

class InputValidator
{
    const ERROR_EMPTY_NAME = 'empty_name';
    const ERROR_EMPTY_DESCRIPTION = 'empty_description';
    const ERROR_EMPTY_DUE_AT = 'empty_due_at';

    /**
     * @param Todo $todo
     * @throws InvalidInputException
     */
    public function validate(Todo $todo)
    {
        $errorMessage = [];
        $errorCodes = [];
        if (empty($todo->getName())) {
            array_push($errorMessage, 'Name is missing.');
            array_push($errorCodes, self::ERROR_EMPTY_NAME);
        }
        if (empty($todo->getDescription())) {
            array_push($errorMessage, 'Description is missing.');
            array_push($errorCodes, self::ERROR_EMPTY_DESCRIPTION);
        }
        if (empty($todo->getDueAt())) {
            array_push($errorMessage, 'Due date is missing.');
            array_push($errorCodes, self::ERROR_EMPTY_DUE_AT);
        }
        if (!empty($errorMessage)) {
            $invalidInputException = new InvalidInputException(implode(', ', $errorMessage));
            $invalidInputException->setErrorCodes($errorCodes);
            throw $invalidInputException;
        }
    }
}

<?php

namespace ToDoApp\Validator;

use ToDoApp\Entity\Todo;
use ToDoApp\Exception\InvalidInputException;

class InputValidator
{
    const ERROR_EMPTY_NAME = 'empty_name';
    const ERROR_EMPTY_DESCRIPTION = 'empty_description';
    const ERROR_EMPTY_DUE_AT = 'empty_due_at';
    const ERROR_INVALID_DUE_AT = 'invalid_due_at';

    /**
     * @param Todo $todo
     * @throws InvalidInputException
     */
    public function validate(Todo $todo)
    {
        $errorCodes = [];

        if (empty($todo->getName())) {
            array_push($errorCodes, self::ERROR_EMPTY_NAME);
        }
        if (empty($todo->getDescription())) {
            array_push($errorCodes, self::ERROR_EMPTY_DESCRIPTION);
        }
        if (empty($todo->getDueAt())) {
            array_push($errorCodes, self::ERROR_EMPTY_DUE_AT);
        } else if (!strtotime($todo->getDueAt())) {
            array_push($errorCodes, self::ERROR_INVALID_DUE_AT);
        }
        if (!empty($errorCodes)) {
            $invalidInputException = new InvalidInputException();
            $invalidInputException->setErrorCodes($errorCodes);
            throw $invalidInputException;
        }
    }
}

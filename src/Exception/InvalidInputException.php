<?php

namespace ToDoApp\Exception;

use Exception;

class InvalidInputException extends Exception
{
    private $errorCodes = [];

    public function getErrorCodes()
    {
        return $this->errorCodes;
    }

    public function setErrorCodes(array $errorCodes)
    {
        $this->errorCodes = $errorCodes;
    }
}
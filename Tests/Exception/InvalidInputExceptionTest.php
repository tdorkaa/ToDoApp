<?php

namespace Tests\Exception;

use ToDoApp\Exception\InvalidInputException;

class InvalidInputExceptionTest extends \PHPUnit\Framework\TestCase
{

    /**
     * @test
     */
    public function getErrorCodes_GivenErrorCodesWereNotAdded_ReturnsEmptyArray()
    {
        $invalidInputException = new InvalidInputException();
        $errorCodes = $invalidInputException->getErrorCodes();
        $this->assertEquals([], $errorCodes);
    }

    /**
     * @test
     */
    public function getErrorCodes_GivenErrorCodesAdded_ReturnsArrayWithAddedErrorCodes()
    {
        $invalidInputException = new InvalidInputException();
        $expectedErrorCodes = [1, 2, 3];
        $invalidInputException->setErrorCodes($expectedErrorCodes);
        $actualErrorCodes = $invalidInputException->getErrorCodes();
        $this->assertEquals($expectedErrorCodes, $actualErrorCodes);
    }
}

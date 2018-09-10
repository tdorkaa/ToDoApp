<?php

use PHPUnit\Framework\TestCase;
use ToDoApp\Entity\Todo;
use ToDoApp\Exception\InvalidInputException;
use ToDoApp\Validator\InputValidator;

class InputValidatorTest extends TestCase
{

    /**
     * @test
     */
    public function validate_AllInputsAreValid_DoesNotThrowException()
    {
        $inputValidator = new InputValidator();
        $inputValidator->validate(new Todo(1, 'todo name', 'todo description', '2018-08-29 10:00:00'));
        $this->expectNotToPerformAssertions();
    }

    /**
     * @test
     */
    public function validate_NameInputIsMissing_ThrowsException()
    {
        $inputValidator = new InputValidator();
        $this->expectException(InvalidInputException::class);
        $this->expectExceptionMessage('Name is missing.');
        $inputValidator->validate(new Todo(1, '', 'todo description', '2018-08-29 10:00:00'));
    }

    /**
     * @test
     */
    public function validate_DescriptionInputIsMissing_ThrowsException()
    {
        $inputValidator = new InputValidator();
        $this->expectException(InvalidInputException::class);
        $this->expectExceptionMessage('Description is missing.');
        $inputValidator->validate(new Todo(1, 'name todo', '', '2018-08-29 10:00:00'));
    }
}
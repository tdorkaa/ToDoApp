<?php

use PHPUnit\Framework\TestCase;
use ToDoApp\Entity\Todo;
use ToDoApp\Exception\InvalidInputException;
use ToDoApp\Validator\InputValidator;

class InputValidatorTest extends TestCase
{
    /**
     * @var InputValidator
     */
    private $inputValidator;

    protected function setUp()
    {
        $this->inputValidator = new InputValidator();
    }

    /**
     * @test
     */
    public function validate_AllInputsAreValid_DoesNotThrowException()
    {
        $this->inputValidator->validate(new Todo(1, 'todo name', 'todo description', '2018-08-29 10:00:00'));
        $this->expectNotToPerformAssertions();
    }

    /**
     * @test
     */
    public function validate_NameInputIsMissing_ThrowsException()
    {
        try {
            $this->inputValidator->validate(new Todo(1, '', 'todo description', '2018-08-29 10:00:00'));
            $this->fail('An exception was expected');
        } catch (InvalidInputException $exception) {
            $actualErrorCodes= $exception->getErrorCodes();
            $this->assertEquals([InputValidator::ERROR_EMPTY_NAME], $actualErrorCodes);
        }
    }

    /**
     * @test
     */
    public function validate_DescriptionInputIsMissing_ThrowsException()
    {
        try {
            $this->inputValidator->validate(new Todo(1, 'todo name', '', '2018-08-29 10:00:00'));
            $this->fail('An exception was expected');
        } catch (InvalidInputException $exception) {
            $actualErrorCodes= $exception->getErrorCodes();
            $this->assertEquals([InputValidator::ERROR_EMPTY_DESCRIPTION], $actualErrorCodes);
        }
    }

    /**
     * @test
     */
    public function validate_DueAtInputIsMissing_ThrowsException()
    {
        try {
            $this->inputValidator->validate(new Todo(1, 'todo name', 'todo description', ''));
            $this->fail('An exception was expected');
        } catch (InvalidInputException $exception) {
            $actualErrorCodes= $exception->getErrorCodes();
            $this->assertEquals([InputValidator::ERROR_EMPTY_DUE_AT], $actualErrorCodes);
        }
    }

    /**
     * @test
     */
    public function validate_NameDescriptionAndDueDateInputsAreMissing_ThrowsException()
    {
        try {
            $this->inputValidator->validate(new Todo(null, '', '', ''));
            $this->fail('An exception was expected');
        } catch (InvalidInputException $exception) {
            $actualErrorCodes= $exception->getErrorCodes();
            $this->assertEquals([InputValidator::ERROR_EMPTY_NAME, InputValidator::ERROR_EMPTY_DESCRIPTION, InputValidator::ERROR_EMPTY_DUE_AT], $actualErrorCodes);
        }
    }

    /**
     * @test
     */
    public function validate_ThrowsExceptionDueDateIsNotValid()
    {
        try {
            $this->inputValidator->validate(new Todo(null, 'todo name', 'todo desc', 'invalid date'));
            $this->fail('An exception was expected');
        } catch (InvalidInputException $exception) {
            $actualErrorCodes= $exception->getErrorCodes();
            $this->assertEquals([InputValidator::ERROR_INVALID_DUE_AT], $actualErrorCodes);
        }
    }

    /**
     * @test
     */
    public function validate_GivenDueDateIsValid_DoesNotThrowException()
    {
        $this->inputValidator->validate(new Todo(null, 'todo name', 'todo desc', '2018-01-01'));
        $this->expectNotToPerformAssertions();
    }
}
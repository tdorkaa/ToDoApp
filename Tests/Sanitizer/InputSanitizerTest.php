<?php

namespace Tests\Sanitizer;

use PHPUnit\Framework\TestCase;
use ToDoApp\Entity\Todo;
use ToDoApp\Sanitizer\InputSanitizer;

class InputSanitizerTest extends TestCase
{
    /**
     * @var InputSanitizer
     */
    private $inputSanitizer;

    protected function setUp()
    {
        $this->inputSanitizer = new InputSanitizer();    }

    /**
     * @test
     */
    public function sanitize_GivenTodoIsClean_ReturnsTheOriginalTodo()
    {
        $todo = new Todo(null, 'todo name', 'todo desc', '2018-08-29 10:00:00');
        $actual = $this->inputSanitizer->sanitize($todo);
        $this->assertEquals($todo, $actual);
    }

    /**
     * @test
     */
    public function sanitize_GivenTodoNameBeginsWithExtraSpaces_ReturnsTheTrimmedTodo()
    {
        $todo = new Todo(null, '          todo name', 'todo desc', '2018-08-29 10:00:00');
        $todoSanitized = new Todo(null, 'todo name', 'todo desc', '2018-08-29 10:00:00');
        $actual = $this->inputSanitizer->sanitize($todo);
        $this->assertEquals($todoSanitized, $actual);
    }

    /**
     * @test
     */
    public function sanitize_GivenTodoDescriptionBeginsWithExtraSpaces_ReturnsTheTrimmedTodo()
    {
        $todo = new Todo(null, 'todo name', '            todo desc', '2018-08-29 10:00:00');
        $todoSanitized = new Todo(null, 'todo name', 'todo desc', '2018-08-29 10:00:00');
        $actual = $this->inputSanitizer->sanitize($todo);
        $this->assertEquals($todoSanitized, $actual);
    }

    /**
     * @test
     */
    public function sanitize_GivenTodoDueAtBeginsWithExtraSpaces_ReturnsTheTrimmedTodo()
    {
        $todo = new Todo(null, 'todo name', 'todo desc', '          2018-08-29 10:00:00');
        $todoSanitized = new Todo(null, 'todo name', 'todo desc', '2018-08-29 10:00:00');
        $actual = $this->inputSanitizer->sanitize($todo);
        $this->assertEquals($todoSanitized, $actual);
    }

}
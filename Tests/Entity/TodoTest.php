<?php

namespace Tests\Entity;

use PHPUnit\Framework\TestCase;
use ToDoApp\Entity\Todo;

class TodoTest extends TestCase
{
    /**
     * @test
     */
    public function constructor_GivenFieldValues_ReturnsTodoWithCorrectValues()
    {
        $todo = new Todo(1, 'todo name1', 'todo description1', '2018-08-29 10:00:00');
        $this->assertEquals(1, $todo->getId());
        $this->assertEquals('todo name1', $todo->getName());
        $this->assertEquals('todo description1', $todo->getDescription());
        $this->assertEquals('2018-08-29 10:00:00', $todo->getDueAt());
        $this->assertEquals('incomplete', $todo->getStatus());
    }

    /**
     * @test
     */
    public function constructor_GivenStatusIsInvalid_ThrowsInvalidArgumentException()
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('This status is not valid.');
        new Todo(1, 'todo name1', 'todo description1', '2018-08-29 10:00:00', 'new');
    }

    /**
     * @test
     */
    public function staticFromArray_GivenAssocArrayWithFieldValues_ReturnsTodo()
    {
        $this->assertEquals(
            new Todo(
                2,
                'My todo',
                'my description',
                '2018-08-29 10:00:00',
                'complete'
            ),
            Todo::fromArray([
                'id' => 2,
                'name' => 'My todo',
                'description' => 'my description',
                'due_at' => '2018-08-29 10:00:00',
                'status' => 'complete'
            ])
        );
    }
}
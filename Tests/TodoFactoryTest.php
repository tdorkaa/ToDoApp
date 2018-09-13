<?php

namespace Tests;

use PHPUnit\Framework\TestCase;
use ToDoApp\Entity\Todo;
use ToDoApp\TodoFactory;

class TodoFactoryTest extends TestCase
{
    /**
     * @test
     */
    public function build_GivenAssocArray_ReturnsTodo()
    {
        $arrayToBuild = [
                        'id' => 2,
                        'name' => 'My todo',
                        'description' => 'my description',
                        'due_at' => '2018-08-29 10:00:00',
                        'status' => 'complete'
                        ];
        $todoFactory = new TodoFactory();
        $actual = $todoFactory->build($arrayToBuild);
        $this->assertEquals(new Todo(2, 'My todo', 'my description', '2018-08-29 10:00:00', 'complete'), $actual);
    }

    /**
     * @test
     */
    public function buildList_GivenAssocArray_ReturnsListOfTodos()
    {
        $arrayToBuild = [
            [
            'id' => 2,
            'name' => 'My todo',
            'description' => 'my description',
            'due_at' => '2018-08-29 10:00:00',
            'status' => 'complete'
            ],
            [
                'id' => 3,
                'name' => 'My todo',
                'description' => 'my description',
                'due_at' => '2018-08-29 10:00:00',
                'status' => 'incomplete'
            ],

        ];
        $todoFactory = new TodoFactory();
        $actual = $todoFactory->buildList($arrayToBuild);
        $this->assertEquals([new Todo(2, 'My todo', 'my description',
                    '2018-08-29 10:00:00', 'complete'),
                            new Todo(3, 'My todo', 'my description',
                                '2018-08-29 10:00:00', 'incomplete')], $actual);
    }

}
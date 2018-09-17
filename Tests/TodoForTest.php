<?php

namespace Tests;

class TodoForTest
{
    public static $todo1 = [
        'id' => 1,
        'name' => 'todo name1',
        'description' => 'todo description1',
        'due_at' => '2018-08-29 10:00:00',
        'status' => 'incomplete'
    ];

    public static $todo2 = [
        'id' => 2,
        'name' => 'todo name2',
        'description' => 'todo description2',
        'due_at' => '2018-08-30 10:00:00',
        'status' => 'incomplete'
    ];

    public static $todo3 = [
        'id' => 3,
        'name' => 'todo name3',
        'description' => 'todo description3',
        'due_at' => '2018-08-27 10:00:00',
        'status' => 'incomplete'
    ];

    public static $updatedTodo = [
        'id' => 1,
        'name' => 'updated name1',
        'description' => 'updated description1',
        'due_at' => '2018-08-30 10:00:00',
        'status' => 'incomplete'
    ];
}
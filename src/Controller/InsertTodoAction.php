<?php

namespace ToDoApp\Controller;

use ToDoApp\Entity\Todo;

class InsertTodoAction extends TodoSave
{
    public function saveTodo(Todo $todo)
    {
        $this->dao->addTodo($todo);
    }
}
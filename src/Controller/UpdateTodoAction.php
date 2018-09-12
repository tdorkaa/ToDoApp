<?php

namespace ToDoApp\Controller;

use ToDoApp\Entity\Todo;

class UpdateTodoAction extends TodoSave
{

    public function saveTodo(Todo $todo)
    {
        $this->dao->updateTodo($todo);
    }
}
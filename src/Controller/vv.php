<?php

namespace ToDoApp\Controller;

use Slim\Http\Request;
use Slim\Http\Response;
use ToDoApp\Entity\Todo;

abstract class valami
{
    public function actionSave(Request $request, Response $response, array $args)
    {

        $todo = new Todo(
            $id,
            $request->getParsedBodyParam('name'),
            $request->getParsedBodyParam('description'),
            $request->getParsedBodyParam('due_at')
        );
        $error = '';
        try {
            $this->inputValidator->validate($todo);
            $this->saveTodo($todo);
        } catch (InvalidInputException $exception) {
            $error = $exception->getMessage();
        }

        $url = '/' . ($error ? '?errors=' . $error : '');
        return $response->withRedirect($url, 301);
    }

    abstract public function saveTodo(Todo $todo);
}

class InsertTodo extends TodoSave
{

    public function saveTodo(Todo $todo)
    {
        $this->dao->addTodo($todo);
    }
}

class UpdateTodoAction extends TodoSave
{

    public function saveTodo(Todo $todo)
    {
        $this->dao->addTodo($todo);
    }
}
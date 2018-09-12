<?php

namespace ToDoApp\Controller;

use Slim\Http\Request;
use Slim\Http\Response;
use Slim\Views\Twig;
use ToDoApp\Dao\TodosDao;
use ToDoApp\Entity\Todo;
use ToDoApp\Exception\InvalidInputException;
use ToDoApp\Validator\InputValidator;

class Todos
{
    /**
     * @var TodosDao
     */
    private $dao;
    /**
     * @var Twig
     */
    private $twig;
    /**
     * @var InputValidator
     */
    private $inputValidator;

    public function __construct(TodosDao $dao, Twig $twig, InputValidator $inputValidator)
    {
        $this->dao = $dao;
        $this->twig = $twig;
        $this->inputValidator = $inputValidator;
    }

    public function actionIndex(Request $request, Response $response, array $args)
    {
        $this->twig->render($response, 'todos.html.twig', ['todos' => $this->dao->listTodos()]);
    }

    public function actionAdd(Request $request, Response $response, array $args)
    {

        $todo = new Todo(
            null,
            $request->getParsedBodyParam('name'),
            $request->getParsedBodyParam('description'),
            $request->getParsedBodyParam('due_at')
        );
        $error = '';
        try {
            $this->inputValidator->validate($todo);
            $this->dao->addTodo($todo);
        } catch (InvalidInputException $exception) {
            $error = $exception->getMessage();
        }

        $url = '/' . ($error ? '?errors=' . $error : '');
        return $response->withRedirect($url, 301);
    }

    public function actionComplete(Request $request, Response $response, array $args)
    {
        $this->dao->setComplete($args['id']);
        return $response->withRedirect('/', 301);
    }

    public function actionDelete(Request $request, Response $response, array $args)
    {
        $this->dao->deleteTodo($args['id']);
        return $response->withRedirect('/', 301);
    }

    public function actionUpdateIndex(Request $request, Response $response, array $args)
    {
        $this->twig->render($response, 'todo-update.html.twig', ['todo' => $this->dao->findById($args['id'])]);
    }

    public function actionUpdate(Request $request, Response $response, array $args)
    {
        $this->dao->updateTodo(new Todo(
            $args['id'],
            $request->getParsedBodyParam('name'),
            $request->getParsedBodyParam('description'),
            $request->getParsedBodyParam('due_at')
        ));
        return $response->withRedirect('/', 301);
    }
}
<?php

namespace ToDoApp\Controller;

use Slim\Http\Request;
use Slim\Http\Response;
use Slim\Views\Twig;
use ToDoApp\Dao\TodosDao;

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

    public function __construct(TodosDao $dao, Twig $twig)
    {
        $this->dao = $dao;
        $this->twig = $twig;
    }

    public function actionIndex(Request $request, Response $response, array $args)
    {
        $this->twig->render($response, 'todos.html.twig', ['todos' => $this->dao->listTodos()]);
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
}
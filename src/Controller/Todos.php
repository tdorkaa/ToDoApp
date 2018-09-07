<?php

namespace ToDoApp\Controller;

use Slim\Http\Request;
use Slim\Http\Response;
use Slim\Views\Twig;
use ToDoApp\Dao\TodosDao;
use ToDoApp\Entity\Todo;

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

    public function actionAdd(Request $request, Response $response, array $args)
    {
        $this->dao->addTodo(
            new Todo(
                null,
                $request->getParsedBodyParam('name'),
                $request->getParsedBodyParam('description'),
                $request->getParsedBodyParam('due_at')
            )
        );

        return $response->withRedirect('/', 301);
    }
}
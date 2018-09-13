<?php

namespace ToDoApp\Controller;

use Slim\Csrf\Guard;
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
    /**
     * @var Guard
     */
    private $csrf;

    public function __construct(TodosDao $dao, Twig $twig, Guard $csrf)
    {
        $this->dao = $dao;
        $this->twig = $twig;
        $this->csrf = $csrf;
    }

    public function actionIndex(Request $request, Response $response, array $args)
    {
        $errors = $request->getParam('errors');

        $twigParameters = [
            'todos' => $this->dao->listTodos(),
        ];
        if ($errors) {
            $twigParameters['errors'] = $errors;
        }
        $twigParameters = $this->setTwigParameters($request, $twigParameters);
        $this->twig->render($response, 'todos.html.twig', $twigParameters);
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
        $twigParameters = [
            'todo' => $this->dao->findById($args['id'])
        ];
        $twigParameters = $this->setTwigParameters($request, $twigParameters);
        $this->twig->render($response, 'todo-update.html.twig', $twigParameters);
    }

    private function setTwigParameters(Request $request, array $twigParameters)
    {
        $csrfConfig = [
            'nameKey' => $this->csrf->getTokenNameKey(),
            'valueKey' => $this->csrf->getTokenValueKey(),
            'name' => $request->getAttribute($this->csrf->getTokenNameKey()),
            'value' => $request->getAttribute($this->csrf->getTokenValueKey())
        ];
        return array_merge($csrfConfig, $twigParameters);
    }
}
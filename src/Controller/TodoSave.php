<?php

namespace ToDoApp\Controller;


use Slim\Http\Request;
use Slim\Http\Response;
use Slim\Views\Twig;
use ToDoApp\Dao\TodosDao;
use ToDoApp\Entity\Todo;
use ToDoApp\Exception\InvalidInputException;
use ToDoApp\Validator\InputValidator;

abstract class TodoSave
{

    /**
     * @var TodosDao
     */
    protected $dao;
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

    public function actionSave(Request $request, Response $response, array $args)
    {

        $todo = new Todo(
            $args['id'] ? $args['id'] : null,
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
        //errors[]=1&errors[]=2&errors[]=foobar
        $url = '/' . ($error ? '?errors=' . $error : '');
        return $response->withRedirect($url, 301);
    }

    abstract public function saveTodo(Todo $todo);
}
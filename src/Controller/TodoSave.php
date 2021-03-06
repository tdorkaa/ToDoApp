<?php

namespace ToDoApp\Controller;


use Slim\Http\Request;
use Slim\Http\Response;
use Slim\Views\Twig;
use ToDoApp\Dao\TodosDao;
use ToDoApp\Entity\Todo;
use ToDoApp\Exception\InvalidInputException;
use ToDoApp\Sanitizer\InputSanitizer;
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
    /**
     * @var InputSanitizer
     */
    private $inputSanitizer;

    public function __construct(TodosDao $dao, Twig $twig, InputValidator $inputValidator, InputSanitizer $inputSanitizer)
    {

        $this->dao = $dao;
        $this->twig = $twig;
        $this->inputValidator = $inputValidator;
        $this->inputSanitizer = $inputSanitizer;
    }

    public function actionSave(Request $request, Response $response, array $args)
    {

        $todo = new Todo(
            $args['id'] ? $args['id'] : null,
            $request->getParsedBodyParam('name'),
            $request->getParsedBodyParam('description'),
            $request->getParsedBodyParam('due_at')
        );
        $errors = [];
        try {
            $sanitizedTodo = $this->inputSanitizer->sanitize($todo);
            $this->inputValidator->validate($sanitizedTodo);
            $this->saveTodo($sanitizedTodo);
        } catch (InvalidInputException $exception) {
            $errors = $exception->getErrorCodes();
        }
        $url = '/' . ($errors ? '?errors[]=' . implode('&errors[]=', $errors) : '');
        return $response->withRedirect($url, 301);
    }

    abstract public function saveTodo(Todo $todo);
}
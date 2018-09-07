<?php

namespace ToDoApp\Controller;

use Slim\Http\Request;
use Slim\Http\Response;
use ToDoApp\Dao\TodosDao;
use ToDoApp\Entity\Todo;

class Todos
{
    /**
     * @var TodosDao
     */
    private $dao;

    public function __construct(TodosDao $dao)
    {
        $this->dao = $dao;
    }

    public function actionIndex(Request $request, Response $response, array $args)
    {
        return $response->write(
            json_encode(
                array_map(
                    function (Todo $todo) {
                        return [
                            'name' => $todo->getName(),
                            'description' => $todo->getDescription(),
                            'due_at' => $todo->getDueAt(),
                            'status' => $todo->getStatus(),
                        ];
                    },
                    $this->dao->listTodos()
                ),
                JSON_PRETTY_PRINT
            )
        );
    }
}
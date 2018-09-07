<?php

namespace ToDoApp\Entity;

class Todo
{
    private $name;
    private $description;
    private $status;
    private $due_at;

    public static function fromArray($todo)
    {
        return new Todo($todo['name'], $todo['description'], $todo['due_at'], $todo['status'] );
    }

    /**
     * Todo constructor.
     * @param $name
     * @param $description
     * @param $status
     * @param $due_at
     */
    public function __construct($name, $description, $due_at, $status = 'incomplete')
    {
        $this->name = $name;
        $this->description = $description;
        $this->setStatus($status);
        $this->due_at = $due_at;
    }

    /**
     * @return mixed
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @return mixed
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * @return Status
     */
    public function getStatus()
    {
        return $this->status;
    }

    private function setStatus($status)
    {
        if (!Status::isValid($status)) {
            throw new \InvalidArgumentException('This status is not valid.');
        }
        $this->status = $status;
    }

    /**
     * @return mixed
     */
    public function getDueAt()
    {
        return $this->due_at;
    }


}
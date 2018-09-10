<?php

namespace ToDoApp\Entity;

class Todo
{
    private $name;
    private $description;
    private $status;
    private $due_at;
    private $id;

    public static function fromArray($todo)
    {
        return new Todo($todo['id'], $todo['name'], $todo['description'], $todo['due_at'], $todo['status']);
    }

    public function __construct($id, $name, $description, $due_at, $status = 'incomplete')
    {
        $this->name = $name;
        $this->description = $description;
        $this->setStatus($status);
        $this->due_at = $due_at;
        $this->id = $id;
    }

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
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

    public function getTodoAsAnAssocArray() {
        return get_object_vars($this);
    }
}
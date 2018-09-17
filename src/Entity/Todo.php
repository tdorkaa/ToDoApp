<?php

namespace ToDoApp\Entity;

class Todo
{
    private $name;
    private $description;
    private $status;
    private $due_at;
    private $id;

    public function __construct($id, $name, $description, $due_at, $status = 'incomplete')
    {
        $this->name = $name;
        $this->description = $description;
        $this->status = $status;
        $this->due_at = $due_at;
        $this->id = $id;
    }

    /**
     * @param mixed $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * @param mixed $description
     */
    public function setDescription($description)
    {
        $this->description = $description;
    }

    /**
     * @param mixed $due_at
     */
    public function setDueAt($due_at)
    {
        $this->due_at = $due_at;
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
     * @return String
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * @return mixed
     */
    public function getDueAt()
    {
        return $this->due_at;
    }
}
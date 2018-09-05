<?php

namespace ToDoApp;

use Dotenv\Dotenv;

class EnvironmentLoader
{
    public function load()
    {
        if (getenv('APPLICATION_ENV') == 'test') {
            $dotenvFile = 'test.env';
        } else {
            $dotenvFile = 'development.env';
        }

        $dotenv = new Dotenv(__DIR__ . '/../config', $dotenvFile);
        $dotenv->load();
    }
}
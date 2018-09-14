<?php

use ToDoApp\AppBuilder;

require __DIR__ . '/../bootstrap.php';

$app = AppBuilder::build();
$app->run();

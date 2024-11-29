<?php

spl_autoload_register(function ($class) {
    require __DIR__ . "/src/$class.php";
});
set_error_handler("ErrorHandler::handleError");
set_exception_handler("ErrorHandler::handleException");

header("Access-Control-Allow-Origin: http://localhost:5173");
header("Content-type: application/json; charset=UTF-8");

$database = new Database("localhost", "cadfem", "cadfem", "");

$personHandler = new PersonGateway($database);

$personController = new PersonController($personHandler);

$parts = explode("/", $_SERVER["REQUEST_URI"]);

$id = $parts[array_key_last($parts)] ?? null;

echo $id;

exit();

$personController->processRequest($_SERVER["REQUEST_METHOD"], $id);

// $personHandler->create($data)


?>
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

$data = (array) json_decode(file_get_contents("php://input"), true);
// $data = $_POST;

$personController->processRequest($_SERVER["REQUEST_METHOD"], "0");

// $personHandler->create($data)


?>
<?php
header("Access-Control-Allow-Origin: http://localhost:5173");

spl_autoload_register(function ($class) {
    require __DIR__ . "/src/$class.php";
});
set_error_handler("ErrorHandler::handleError");
set_exception_handler("ErrorHandler::handleException");

header("Content-type: application/json; charset=UTF-8");

$database = new Database("localhost", "cadfem", "cadfem", "");

$personHandler = new PersonGateway($database);

$personController = new PersonController($personHandler);

// $data = (array) json_decode(file_get_contents("php://input"), true);
// $data = $_POST;

// $data = (array) json_decode(
//     '{
//     "fname":"Dawid",
//     "second_name":"D\u0119bkowski",
//     "department":"Informatyk",
//     "descr":"Zrobi\u0142 strone",
//     "image_path":"\/zdjecia\/osoby\/dawiddebkowski.png"
//     }');

$personController->processRequest($_SERVER["REQUEST_METHOD"], "0");

// echo json_encode($data);
// exit();

// $personHandler->create($data)


?>
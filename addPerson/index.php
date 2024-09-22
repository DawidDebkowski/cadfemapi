<?php
header("Access-Control-Allow-Origin: http://localhost:5173");
header("Access-Control-Allow-Methods: POST, GET, PATCH, OPTIONS, DELETE");
header("Allow: GET, PATCH, DELETE, POST, OPTIONS");

spl_autoload_register(function ($class) {
    require __DIR__ . "/src/$class.php";
set_error_handler("ErrorHandler::handleError");
});
set_exception_handler("ErrorHandler::handleException");

header("Content-type: application/json; charset=UTF-8");

if($_SERVER["REQUEST_METHOD"] == "OPTIONS") {
    http_response_code(200);
    header("Access-Control-Allow-Methods: POST, GET, PATCH, OPTIONS, DELETE");
    header("Allow: GET, PATCH, DELETE, POST, OPTIONS");
    exit();
}

$database = new Database("localhost", "cadfem", "cadfem", "");

$personHandler = new PersonGateway($database);

$personController = new PersonController($personHandler);

$parts = explode("/", $_SERVER["REQUEST_URI"]);

$id = $parts[array_key_last($parts)] ?? null;

$personController->processRequest($_SERVER["REQUEST_METHOD"], $id);
// $data = (array) json_decode(
//     '{
//     "id":1,
//     "fname":"Dawid",
//     "second_name":"D\u0119bkowski",
//     "department":"Informatyk",
//     "descr":"Zrobi\u0142 strone123",
//     "image_path":"\/zdjecia\/osoby\/dawiddebkowski.png"
//     }');

// echo json_encode($data);
// $personController->processRequest("PATCH", $data["id"]);
?>
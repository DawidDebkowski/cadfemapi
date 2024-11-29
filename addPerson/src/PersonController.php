<?php

class PersonController
{
    private PersonGateway $gateway;

    public function __construct(PersonGateway $gateway)
    {
        $this->gateway = $gateway;
    }

    public function processRequest(string $method, ?string $id): void
    {
        if ($id) {
            $this->processResourceRequest($method, $id);
        } else {
            $this->processCollectionRequest($method);
        }
    }

    private function processResourceRequest(string $method, string $id): void
    {
        $person = $this->gateway->get($id);

        if (!$person) {
            http_response_code(404);
            echo json_encode(["message" => "Person not found"]);
            return;
        }

        switch ($method) {
            case "GET":
                echo json_encode($person);
                break;

            case "POST": //thats a patch hidden in post because I cant use php//input (too hard :( )
                $data = $_POST;

                $errors = $this->getValidationErrors($data, false);

                if (!empty($errors)) {
                    http_response_code(422);
                    echo json_encode(["errors" => $errors]);
                    break;
                }

                $rows = $this->gateway->update($person, $data);

                echo json_encode([
                    "message" => "Person $id updated",
                    "rows" => $rows
                ]);
                break;

            case "DELETE":
                $rows = $this->gateway->delete($id, $person);

                echo json_encode([
                    "message" => "Person $id deleted",
                    "rows" => $rows
                ]);
                break;

            default:
                http_response_code(405);
                header("Allow: GET, PATCH, DELETE");
        }
    }

    private function processCollectionRequest(string $method): void
    {
        header("Allow: GET, POST, PATCH");
        header("Access-Control-Allow-Origin: http://localhost:5173");
        switch ($method) {
            case "GET":
                echo json_encode($this->gateway->getAll());
                break;

            case "POST":
                $data = $_POST;

                $errors = $this->getValidationErrors($data);

                if (!empty($errors)) {
                    http_response_code(422);
                    // echo $data;
                    echo json_encode(["errors" => $errors]);
                    break;
                }

                $id = $this->gateway->create($data);

                http_response_code(201);
                echo json_encode([
                    "message" => "Member created",
                    "id" => $id
                ]);
                break;
            case "OPTIONS":
                http_response_code(200);
                header("Allow: GET, POST, PATCH");

            default:
                http_response_code(405);
                header("Allow: GET, POST");
        }
    }

    private function getValidationErrors(array $data, bool $is_new = true): array
    {
        $errors = [];

        if ($is_new && empty($data["fname"])) {
            $errors[] = "fname is required";
        }
        if ($is_new && empty($data["second_name"])) {
            $errors[] = "second_name is required";
        }
        if ($is_new && empty($data["department"])) {
            $errors[] = "department is required";
        }
        if ($is_new && empty($data["department"])) {
            $errors[] = "department is required";
        }
        if ($is_new && empty($data["descr"])) {
            $errors[] = "descr is required";
        }

        return $errors;
    }
}

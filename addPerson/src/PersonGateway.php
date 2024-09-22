<?php

class PersonGateway
{
    private PDO $connection;

    public function __construct(Database $database = null)
    {
        $this->connection = $database->getConnection();
    }

    public function create(array $data): string
    {
        $sql = "INSERT INTO members (fname, second_name, department, descr, image_path)
                VALUES (:fname, :second_name, :department, :descr, :image_path)";

        $stmt = $this->connection->prepare($sql);

        $stmt->bindValue(":fname", $data["fname"], PDO::PARAM_STR);
        $stmt->bindValue(":second_name", $data["second_name"], PDO::PARAM_STR);
        $stmt->bindValue(":department", $data["department"], PDO::PARAM_STR);
        $stmt->bindValue(":descr", $data["descr"], PDO::PARAM_STR);

        $img = new ImageUploader("/zdjecia/members/");
        $image_path = $img->uploadFile($_FILES["image"]);
        $stmt->bindValue(":image_path", $image_path, PDO::PARAM_STR);

        $stmt->execute();

        return $this->connection->lastInsertId();
    }

    public function getAll(): array
    {
        $sql = "SELECT *
                FROM members";

        $stmt = $this->connection->query($sql);

        $data = [];

        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $data[] = $row;
        }

        return $data;
    }

    public function get(string $id)
    {
        $sql = "SELECT *
                FROM members
                WHERE id = :id";

        $stmt = $this->connection->prepare($sql);

        $stmt->bindValue(":id", $id, PDO::PARAM_INT);

        $stmt->execute();

        $data = $stmt->fetch(PDO::FETCH_ASSOC);

        return $data;
    }

    public function update(array $current, array $new): int
    {
        $sql = "UPDATE members
                SET fname = :fname, second_name = :second_name, department = :department, descr = :descr, image_path = :image_path
                WHERE id = :id";

        $stmt = $this->connection->prepare($sql);

        $stmt->bindValue(":fname", $new["fname"] ?? $current["fname"], PDO::PARAM_STR);
        $stmt->bindValue(":second_name", $new["second_name"] ?? $current["second_name"], PDO::PARAM_STR);
        $stmt->bindValue(":department", $new["department"] ?? $current["department"], PDO::PARAM_STR);
        $stmt->bindValue(":descr", $new["descr"] ?? $current["descr"], PDO::PARAM_STR);

        if($new["image_path"] != $current["image_path"]) {
            $img = new ImageUploader("/zdjecia/members/");
            $image_path = $img->uploadFile($_FILES["image"]);
            $stmt->bindValue(":image_path", $image_path, PDO::PARAM_STR);
            
            //remove the old one
        } else {
            $stmt->bindValue(":image_path", $current["image_path"], PDO::PARAM_STR);
        }

        $stmt->bindValue(":id", $current["id"], PDO::PARAM_INT);

        $stmt->execute();

        return $stmt->rowCount();
    }

    public function delete(string $id): int
    {
        $sql = "DELETE FROM members
                WHERE id = :id";

        $stmt = $this->connection->prepare($sql);

        $stmt->bindValue(":id", $id, PDO::PARAM_INT);

        $stmt->execute();

        return $stmt->rowCount();
    }
}

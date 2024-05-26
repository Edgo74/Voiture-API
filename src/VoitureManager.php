<?php
// pdo query method vs prepared statement 
//    $stmt->execute(["id" => $id]);
//            $data[] = $row;
class VoitureManager
{
    private PDO $conn;
    public function __construct(Database $database)
    {
        $this->conn = $database->getConnection();
    }

    public function getAll(): array
    {
        $sql = "SELECT * FROM voiture ORDER BY voiture_id DESC";
        $stmt = $this->conn->query($sql);

        $data = [];

        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $row["garantie"] = (bool)$row["garantie"];

            array_push($data, $row);
        }

        return $data;
    }

    public function get(string $id): array| false
    {
        $sql = "SELECT * FROM voiture WHERE voiture_id = :id";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindValue(":id", $id, PDO::PARAM_INT);
        $stmt->execute();
        $data =  $stmt->fetch(PDO::FETCH_ASSOC);

        if ($data !== false) {
            $data["garantie"] = (bool)$data["garantie"];
        }

        return $data;
    }

    public function create(array $data): string
    {
        $sql = "INSERT INTO voiture(titre, year, carburant, kilometre, price, image, immatriculation, type , date, garantie ) 
        VALUES(:titre, :year, :carburant, :kilometre, :price, :image, :immatriculation, :type , :date, :garantie)";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindValue(":titre", $data["titre"], PDO::PARAM_STR);
        $stmt->bindValue(":year", $data["year"], PDO::PARAM_INT);
        $stmt->bindValue(":carburant", $data["carburant"], PDO::PARAM_STR);
        $stmt->bindValue(":kilometre", $data["kilometre"], PDO::PARAM_INT);
        $stmt->bindValue(":price", $data["price"], PDO::PARAM_INT);
        $stmt->bindValue(":image", $data["image"], PDO::PARAM_STR);
        $stmt->bindValue(":immatriculation", $data["immatriculation"], PDO::PARAM_STR);
        $stmt->bindValue(":type", $data["type"], PDO::PARAM_STR);
        $stmt->bindValue(":date", $data["date"] ??  date("Y-m-d"), PDO::PARAM_STR);
        $stmt->bindValue(":garantie", $data["garantie"] ?? 0, PDO::PARAM_BOOL);
        $stmt->execute();

        return $this->conn->lastInsertId();
    }

    public function update(string $id, array $data): int
    {
        $fields = [];

        if (!empty($data["titre"])) {
            $fields["titre"] = [
                $data["titre"],
                PDO::PARAM_STR,
            ];
        }

        if (!empty($data["year"])) {
            $fields["year"] = [
                $data["year"],
                PDO::PARAM_INT,
            ];
        }

        if (!empty($data["carburant"])) {
            $fields["carburant"] = [
                $data["carburant"],
                PDO::PARAM_STR,
            ];
        }

        if (!empty($data["kilometre"])) {
            $fields["kilometre"] = [
                $data["kilometre"],
                PDO::PARAM_INT,
            ];
        }

        if (!empty($data["price"])) {
            $fields["price"] = [
                $data["price"],
                PDO::PARAM_INT,
            ];
        }

        if (!empty($data["image"])) {
            $fields["image"] = [
                $data["image"],
                PDO::PARAM_STR,
            ];
        }

        if (!empty($data["immatriculation"])) {
            $fields["immatriculation"] = [
                $data["immatriculation"],
                PDO::PARAM_STR,
            ];
        }

        if (!empty($data["type"])) {
            $fields["type"] = [
                $data["type"],
                PDO::PARAM_STR,
            ];
        }

        if (!empty($data["date"])) {
            $fields["date"] = [
                $data["date"],
                PDO::PARAM_STR,
            ];
        }

        if (array_key_exists("garantie", $data)) {
            $fields["garantie"] = [
                $data["garantie"],
                PDO::PARAM_BOOL,
            ];
        }

        if (empty($fields)) {

            return 0;
        } else {

            $sets = array_map(function ($value) {
                return "$value = :$value";
            }, array_keys($data));

            $sql = "UPDATE voiture SET " . implode(", ", $sets) . " WHERE voiture_id = :id";
            $stmt = $this->conn->prepare($sql);
            $stmt->bindValue(":id", $id, PDO::PARAM_INT);

            foreach ($fields as $name => $values) {
                $stmt->bindValue(":$name", $values[0], $values[1]);
            }

            $stmt->execute();

            return $stmt->rowCount();
            // echo $sql;
            // exit;
        }



        // // print_r($fields);
        // print_r(array_keys($fields));
        // exit;
    }


    public function delete(string $id): int
    {
        $sql = "DELETE FROM voiture WHERE voiture_id = :id";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindValue(":id", $id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->rowCount();
    }
}

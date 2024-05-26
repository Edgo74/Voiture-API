<?php

class UtilisateurAPIManager
{
    private PDO $conn;

    public function __construct(Database $database)
    {
        $this->conn = $database->getConnection();
    }

    public function getByApiKey(string $api_key): array | false
    {
        $sql = "SELECT * FROM utilisateur WHERE api_key = :api_key";

        $stmt = $this->conn->prepare($sql);

        $stmt->bindValue(":api_key", $api_key, PDO::PARAM_STR);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}

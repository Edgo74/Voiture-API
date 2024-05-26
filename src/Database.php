<?php

class Database
{
    private ?PDO $conn = null;
    public function __construct(
        private string $host,
        private string $dbname,
        private string $username,
        private string $password,
    ) {
    }

    public function getConnection(): PDO
    {
        if ($this->conn === null) {
            $this->conn =  new PDO("mysql:host=$this->host;dbname=$this->dbname;charset=utf8", $this->username, $this->password, [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_EMULATE_PREPARES => false,
                PDO::ATTR_STRINGIFY_FETCHES => false
            ]);
        }

        return $this->conn;
    }
}

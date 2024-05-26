<?php

class Authentification
{
    public function __construct(private UtilisateurAPIManager $APIManager)
    {
    }
    public function authenticateAPIKey(): bool
    {
        if (!isset($_SERVER["HTTP_X_API_KEY"]) || empty($_SERVER["HTTP_X_API_KEY"])) {
            http_response_code(400);
            echo json_encode(["message" => "API Key is missing"]);
            return false;
        }

        $api_key = $_SERVER["HTTP_X_API_KEY"];

        if ($this->APIManager->getByApiKey($api_key) === false) {
            http_response_code(401);
            echo json_encode(["message" => "Invalid API Key"]);
            return false;
        }

        return true;
    }
}

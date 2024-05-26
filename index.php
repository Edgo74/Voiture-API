<?php

declare(strict_types=1);

require __DIR__ . "/bootstrap.php";

//echo  PHP_URL_PATH . "<br>";
$path = parse_url($_SERVER["REQUEST_URI"], PHP_URL_PATH);

$parts = explode("/", $path);

//print_r($parts);

$ressource = $parts[2];

$id = $parts[3] ?? null;

// echo $ressource . ", " . $id;

// echo $_SERVER["REQUEST_METHOD"];


if ($ressource != "voitures") {
    // header("HTTP/1.0 404 Not Found")
    //header("{$_SERVER["SERVER_PROTOCOL"]} 404 Not Found")
    http_response_code(404);
    exit;
}


$database = new Database($_ENV["DB_HOST"], $_ENV["DB_NAME"], $_ENV["DB_USER"], $_ENV["DB_PASS"]);

$APIManager = new UtilisateurAPIManager($database);

$auth = new Authentification($APIManager);

if (!$auth->authenticateAPIKey()) {
    exit;
}



$voiture_manager = new VoitureManager($database);

$controller = new VoitureController($voiture_manager);

$controller->processRequest($_SERVER["REQUEST_METHOD"], $id);

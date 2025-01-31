<?php


require dirname(__DIR__) . "/api/vendor/autoload.php";

set_error_handler("ErrorHandler::handleError");
set_exception_handler("ErrorHandler::handleException");

$dotenv = Dotenv\Dotenv::createImmutable(dirname(__DIR__ . "/api"));
$dotenv->load();


header("Content-Type: application/json; charset=UTF-8");

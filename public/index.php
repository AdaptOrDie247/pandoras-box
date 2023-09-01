<?php

require_once __DIR__ . '/../App/Entities/HttpRequest.php';
require_once __DIR__ . '/../vendor/autoload.php';

use App\Entities\HttpRequest;

// Load the dotenv file.
Dotenv\Dotenv::createImmutable(__DIR__ . '/../')->load();

// Create a new (incoming) HTTP Request object with info from the current request.
$http_request = new HttpRequest();
$http_request->populateInfoFromServerVar($_SERVER);

// Format request info and log it to a file.
$info = $http_request->getInfoTextString();
$log_directory  = __DIR__ . "/../{$_ENV['log.directory']}";
$http_request->logInfoStringToTextFile($info, $log_directory);

// Show the client something.
require_once __DIR__ . '/../App/Views/Home.php';

<?php

// Import files.
require_once __DIR__ . '/../App/Entities/Database.php';
require_once __DIR__ . '/../App/Entities/HttpClient.php';
require_once __DIR__ . '/../App/Entities/HttpRequest.php';

// Load Composer dependencies.
require_once __DIR__ . '/../vendor/autoload.php';

// Import namespaces.
use App\Entities\Database;
use App\Entities\HttpClient;
use App\Entities\HttpRequest;

// Load the dotenv file.
Dotenv\Dotenv::createImmutable(__DIR__ . '/../')->load();

// Initialize the database.
$database_path  = __DIR__ . "/../{$_ENV['database.directory']}/{$_ENV['database.name']}";
$database       = new Database($database_path);
$database->addMigration(HttpRequest::getDatabaseMigration());
$database->addMigration(HttpClient::getDatabaseMigration());
$database->runMigrations();

// Get and save the HTTP request.
$http_request = new HttpRequest();
$http_request->populateFromServerVar($_SERVER);
$http_request->saveToDatabase($database);

// Get the HTTP client and save if unique.
$http_client = new HttpClient();
$http_client->populateFromHttpRequest($http_request);
if ($http_client->isUniqueInDatabase($database)) {
  $http_client->saveToDatabase($database);
}

// Show the client something.
require_once __DIR__ . '/../App/Views/Home.php';

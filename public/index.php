<?php

// Require code from other files.
require_once __DIR__ . '/../App/Database/Connectors/HttpRequest.php';
require_once __DIR__ . '/../App/Database/Migrations/HttpRequest.php';
require_once __DIR__ . '/../App/Entities/HttpRequest.php';
require_once __DIR__ . '/../App/Entities/SQLite3Database.php';
require_once __DIR__ . '/../vendor/autoload.php';

// Import namespaces.
use App\Database\Connectors\HttpRequest as HttpRequestConnector;
use App\Database\Migrations\HttpRequest as HttpRequestMigration;
use App\Entities\HttpRequest as HttpRequestEntity;
use App\Entities\SQLite3Database;

// Load the dotenv file.
Dotenv\Dotenv::createImmutable(__DIR__ . '/../')->load();

// Pull configs from dotenv file.
$db_filepath      = __DIR__ . "/../{$_ENV['database.directory']}/{$_ENV['database.name']}";
$local_time_zone  = new DateTimeZone($_ENV['time_zone']);

// Create a new (incoming) HTTP request entity with info from the current request.
$http_request_entity = new HttpRequestEntity();
$http_request_entity->populateInfoFromServerVar($_SERVER, $local_time_zone);

// Configure the database.
$db_migrations = [
  new HttpRequestMigration(),
];
$database = new SQLite3Database($db_filepath, $db_migrations);

// Save the HTTP request entity.
$db_connector = new HttpRequestConnector();
$database->saveEntity($http_request_entity, $db_connector);

// Show the client something.
require_once __DIR__ . '/../App/Views/Home.php';

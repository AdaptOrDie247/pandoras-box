#!/usr/bin/env php
<?php

require_once __DIR__ . '/../App/Entities/Database.php';
require_once __DIR__ . '/../App/Entities/HttpClient.php';

// Load Composer dependencies.
require_once __DIR__ . '/../vendor/autoload.php';

use App\Entities\Database;
use App\Entities\HttpClient;

// Load the dotenv file.
Dotenv\Dotenv::createImmutable(__DIR__ . '/../')->load();

// Initialize the database.
$database_path  = __DIR__ . "/../{$_ENV['database.directory']}/{$_ENV['database.name']}";
$database       = new Database($database_path);

// Get HTTP clients from the database.
$http_client_model      = new HttpClient();
$http_client_rows       = $http_client_model->getHttpClientsFromDatabase($database);
$http_client_rows_count = count($http_client_rows);

// Print HTTP client count header.
echo "\n{$http_client_rows_count} HTTP Clients:\n\n";

// Print HTTP client table.
printf("%-10s %-50s %s\n", 'ROW ID', 'IP ADDRESS', 'USER AGENT');
printf("%-10s %-50s %s\n", str_repeat('=', 10), str_repeat('=', 50), str_repeat('=', 80));
foreach ($http_client_rows as $http_client_row) {

  printf("%-10s %-50s %s\n", $http_client_row['rowid'], $http_client_row['remote_address'], $http_client_row['http_user_agent']);

}
echo PHP_EOL;

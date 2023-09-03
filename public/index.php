<?php

// Require code from other files.
require_once __DIR__ . '/../App/Connectors/HttpRequest.php';
require_once __DIR__ . '/../App/Database/Migrations/HttpRequest.php';
require_once __DIR__ . '/../App/Database/Migrations/UniqueVisitor.php';
require_once __DIR__ . '/../App/Entities/HttpRequest.php';
require_once __DIR__ . '/../App/Entities/UniqueVisitor.php';
require_once __DIR__ . '/../App/Models/BaseModel.php';
require_once __DIR__ . '/../App/Models/HttpRequest.php';
require_once __DIR__ . '/../App/Models/UniqueVisitor.php';
require_once __DIR__ . '/../vendor/autoload.php';

// Import namespaces.
use App\Connectors\HttpRequest as HttpRequestConnector;
use App\Database\Migrations\HttpRequest as HttpRequestMigration;
use App\Database\Migrations\UniqueVisitor as UniqueVisitorMigration;
use App\Entities\HttpRequest as HttpRequestEntity;
use App\Entities\UniqueVisitor as UniqueVisitorEntity;
use App\Models\BaseModel;
use App\Models\HttpRequest as HttpRequestModel;
use App\Models\UniqueVisitor as UniqueVisitorModel;

// Load the dotenv file.
Dotenv\Dotenv::createImmutable(__DIR__ . '/../')->load();

// Create a new (incoming) HTTP request entity with info from the current request.
$http_request_entity  = new HttpRequestEntity();
$local_time_zone      = new DateTimeZone($_ENV['time_zone']);
$http_request_entity->populateInfoFromServerVar($_SERVER, $local_time_zone);

// Configure the database.
$db_filepath    = __DIR__ . "/../{$_ENV['database.directory']}/{$_ENV['database.name']}";
$db_migrations  = [
  new HttpRequestMigration(),
  new UniqueVisitorMigration(),
];
$database = new BaseModel($db_filepath);
$database->runMigrations($db_migrations);

// Save the HTTP request entity.
$http_request_model     = new HttpRequestModel();
$http_request_connector = new HttpRequestConnector();
$database->saveEntity($http_request_entity, $http_request_model, $http_request_connector);

// Save unique visitor entity if applicable.
$unique_visitor_model   = new UniqueVisitorModel($db_filepath);
$unique_visitor_entity  = new UniqueVisitorEntity(
  $http_request_entity->remote_address->value,
  $http_request_entity->http_user_agent->value
);
if (!$unique_visitor_model->hasUniqueVisitorEntity($unique_visitor_entity)) {

} else {

}

// Show the client something.
require_once __DIR__ . '/../App/Views/Home.php';

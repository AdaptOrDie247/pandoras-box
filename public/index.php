<?php

importFiles();

// Import namespaces.
use App\Connectors\HttpRequest as HttpRequestConnector;
use App\Connectors\UniqueVisitor as UniqueVisitorConnector;
use App\Database\Migrations\HttpRequest as HttpRequestMigration;
use App\Database\Migrations\UniqueVisitor as UniqueVisitorMigration;
use App\Entities\HttpRequest as HttpRequestEntity;
use App\Entities\UniqueVisitor as UniqueVisitorEntity;
use App\Models\BaseModel;
use App\Models\HttpRequest as HttpRequestModel;
use App\Models\UniqueVisitor as UniqueVisitorModel;

// Load the dotenv file.
Dotenv\Dotenv::createImmutable(__DIR__ . '/../')->load();

// Specify and initialize database.
$db_filepath  = __DIR__ . "/../{$_ENV['database.directory']}/{$_ENV['database.name']}";
$base_model   = initializeDatabase($db_filepath);

// Get and save HTTP request entity.
$http_request_entity  = getHttpRequestEntity();
saveHttpRequestEntity($base_model, $http_request_entity);

// Get and save unique visitor entity if doesn't exist in database.
$unique_visitor_entity      = new UniqueVisitorEntity($http_request_entity->remote_address, $http_request_entity->http_user_agent);
$unique_visitor_model       = new UniqueVisitorModel($db_filepath);
$unique_visitor_connector   = new UniqueVisitorConnector();
saveUniqueVisitorEntityIfNotExists($base_model, $unique_visitor_entity, $unique_visitor_model, $unique_visitor_connector);

// Show the client something.
require_once __DIR__ . '/../App/Views/Home.php';

function importFiles(): void {

  require_once __DIR__ . '/../App/Connectors/HttpRequest.php';
  require_once __DIR__ . '/../App/Connectors/UniqueVisitor.php';
  require_once __DIR__ . '/../App/Database/Migrations/HttpRequest.php';
  require_once __DIR__ . '/../App/Database/Migrations/UniqueVisitor.php';
  require_once __DIR__ . '/../App/Entities/HttpRequest.php';
  require_once __DIR__ . '/../App/Entities/UniqueVisitor.php';
  require_once __DIR__ . '/../App/Models/BaseModel.php';
  require_once __DIR__ . '/../App/Models/HttpRequest.php';
  require_once __DIR__ . '/../App/Models/UniqueVisitor.php';
  require_once __DIR__ . '/../vendor/autoload.php';

}

function getHttpRequestEntity(): HttpRequestEntity {

  $http_request_entity  = new HttpRequestEntity();
  $local_time_zone      = new DateTimeZone($_ENV['time_zone']);
  $http_request_entity->populateInfoFromServerVar($_SERVER, $local_time_zone);
  return $http_request_entity;

}

function initializeDatabase(string $db_filepath): BaseModel {

  $db_migrations  = [
    new HttpRequestMigration(),
    new UniqueVisitorMigration(),
  ];
  $database = new BaseModel($db_filepath);
  $database->runMigrations($db_migrations);
  return $database;

}

function saveHttpRequestEntity(BaseModel $base_model, HttpRequestEntity $http_request_entity): void {

  $http_request_model     = new HttpRequestModel();
  $http_request_connector = new HttpRequestConnector();
  $base_model->saveEntity($http_request_entity, $http_request_model, $http_request_connector);

}

function saveUniqueVisitorEntityIfNotExists(BaseModel $base_model, UniqueVisitorEntity $entity, UniqueVisitorModel $model, UniqueVisitorConnector $connector): void {

  $unique_visitor_exists = $model->hasUniqueVisitorEntity($entity);
  if (!$unique_visitor_exists) {
    $base_model->saveEntity($entity, $model, $connector);
  }

}

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
$http_request  = getHttpRequestEntity($_SERVER, $_ENV['time_zone']);
saveHttpRequestEntity($base_model, $http_request);

// Get and save unique visitor entity if doesn't exist in database.
$unique_visitor = new UniqueVisitorEntity($http_request->remote_address, $http_request->http_user_agent);
saveUniqueVisitorEntityIfNotExists($base_model, $unique_visitor);

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

function getHttpRequestEntity(array $server, string $time_zone): HttpRequestEntity {

  $http_request_entity  = new HttpRequestEntity();
  $local_time_zone      = new DateTimeZone($time_zone);
  $http_request_entity->populateInfoFromServerVar($server, $local_time_zone);
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

function saveHttpRequestEntity(BaseModel $base_model, HttpRequestEntity $entity): void {

  $model        = new HttpRequestModel();
  $connector    = new HttpRequestConnector();
  $base_model->saveEntity($entity, $model, $connector);

}

function saveUniqueVisitorEntityIfNotExists(BaseModel $base_model, UniqueVisitorEntity $entity): void {

  $model       = new UniqueVisitorModel($base_model->db_filepath);
  $connector   = new UniqueVisitorConnector();

  $unique_visitor_exists = $model->hasUniqueVisitorEntity($entity);
  if (!$unique_visitor_exists) {
    $base_model->saveEntity($entity, $model, $connector);
  }

}

<?php

namespace App\Entities;

require_once __DIR__ . '/../Entities/Database.php';
require_once __DIR__ . '/../Entities/HttpRequest.php';

use App\Entities\Database;
use App\Entities\HttpRequest;

class HttpClient {

  private $remote_address;
  private $http_user_agent;

  private const DATABASE_MIGRATION = <<< EOF
      
    CREATE TABLE IF NOT EXISTS http_client (
    
      remote_address       TEXT    NOT NULL,
      http_user_agent      TEXT    NOT NULL
    
    );
    
  EOF;

  private const DATABASE_TABLE_NAME = 'http_client';

  private const DATABASE_FIELD_COLUMN_MAP = [

    'remote_address'      => 'remote_address',
    'http_user_agent'     => 'http_user_agent',

  ];

  private const DATABASE_COLUMN_TYPE_MAP = [

    'remote_address'         => 'TEXT',
    'http_user_agent'        => 'TEXT',

  ];

  public function __get(string $name) {

    return $this->$name ?? null;

  }

  public function __set(string $name, $value) {

    $this->$name = $value;

  }

  public static function getDatabaseMigration(): string {

    return self::DATABASE_MIGRATION;

  }

  public static function getDatabaseTableName(): string {

    return self::DATABASE_TABLE_NAME;

  }

  public static function getDatabaseFieldColumnMap(): array {

    return self::DATABASE_FIELD_COLUMN_MAP;

  }

  public static function getDatabaseColumnTypeMap(): array {

    return self::DATABASE_COLUMN_TYPE_MAP;

  }

  public function populateFromHttpRequest(HttpRequest $http_request) {

    $this->remote_address   = $http_request->remote_address;
    $this->http_user_agent  = $http_request->http_user_agent;

  }

  public function isUniqueInDatabase(Database $database): bool {

    // Generate SQL.
    $sql  = "SELECT * FROM " . self::getDatabaseTableName() . " WHERE" . PHP_EOL;
    $sql .= "remote_address = :remote_address" . PHP_EOL;
    $sql .= "AND" . PHP_EOL;
    $sql .= "http_user_agent = :http_user_agent" . PHP_EOL;

    // Prepare and execute SQL then retrieve result.
    $statement = $database->getDatabase()->prepare($sql);
    $statement->bindValue(':remote_address',    $this->remote_address,   SQLITE3_TEXT);
    $statement->bindValue(':http_user_agent',   $this->http_user_agent,  SQLITE3_TEXT);
    $result         = $statement->execute();
    $result_array   = $result->fetchArray();

    // Return if record is unique in database.
    if ($result_array === false) {
      return true;
    } else {
      return false;
    }

  }

  public function saveToDatabase(Database $database): void {

    $database->saveEntity($this);

  }

}

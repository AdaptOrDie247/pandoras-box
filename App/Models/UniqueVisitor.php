<?php

namespace App\Models;

require_once __DIR__ . '/../Entities/UniqueVisitor.php';

use App\Entities\UniqueVisitor as UniqueVisitorEntity;
use SQLite3;

class UniqueVisitor {

  private $database;
  private $table_name = 'unique_visitor';

  private $field_types = [

    'remote_address'         => 'TEXT',
    'http_user_agent'        => 'TEXT',

  ];

  public function __construct(string $db_filepath = null) {

    if (!is_null($db_filepath)) {
      $this->database = new SQLite3($db_filepath);
    } else {
      $this->database = null;
    }
    
  }

  public function __get(string $name) {

    return $this->$name ?? null;

  }

  public function hasUniqueVisitorEntity(UniqueVisitorEntity $entity): bool {

    // Generate SQL.
    $sql  = "SELECT * FROM {$this->table_name} WHERE" . PHP_EOL;
    $sql .= "remote_address = :remote_address" . PHP_EOL;
    $sql .= "AND" . PHP_EOL;
    $sql .= "http_user_agent = :http_user_agent" . PHP_EOL;

    // Prepare and execute SQL then retrieve result.
    $statement = $this->database->prepare($sql);
    $statement->bindValue(':remote_address',    $entity->remote_address,   SQLITE3_TEXT);
    $statement->bindValue(':http_user_agent',   $entity->http_user_agent,  SQLITE3_TEXT);
    $result         = $statement->execute();
    $result_array   = $result->fetchArray();

    // Return if database already has identical entity.
    if ($result_array === false) {
      return false;
    } else {
      return true;
    }

  }

}

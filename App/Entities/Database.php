<?php

namespace App\Entities;

use SQLite3;

class Database {

  private $database;
  private $migrations;

  public function __construct(string $database_path) {

    $this->database = new SQLite3($database_path);

  }

  public function addMigration(string $migration): void {

    $this->migrations[] = $migration;

  }

  public function runMigrations(): void {

    foreach ($this->migrations as $migration) {
      $this->database->exec($migration);
    }

  }

  public function saveEntity(object $entity): void {

    // Set the required variables.
    $database_table_name      = $entity::getDatabaseTableName();
    $field_column_map         = $entity::getDatabaseFieldColumnMap();
    $database_fields          = array_values($field_column_map);
    $last_database_field      = end($database_fields); reset($database_fields);
    $column_type_map          = $entity::getDatabaseColumnTypeMap();

    // Generate the SQL for a prepared statement.
    $sql  = "INSERT INTO $database_table_name (" . PHP_EOL;
    foreach ($database_fields as $field) {
      $sql .= "\t$field";
      if ($field !== $last_database_field) $sql .= ',';
      $sql .= PHP_EOL;
    }
    $sql .= ') VALUES (' . PHP_EOL;
    foreach ($database_fields as $field) {
      $sql .= "\t:$field";
      if ($field !== $last_database_field) $sql .= ',';
      $sql .= PHP_EOL;
    }
    $sql .= ')' . PHP_EOL;

    // Prepare the SQL statement and bind the values.
    $statement = $this->database->prepare($sql);
    foreach ($field_column_map as $entity_property => $database_field) {
      $param  = ":{$database_field}";
      $value  = $entity->$entity_property;
      $type   = constant('SQLITE3_' . $column_type_map[$database_field]);
      $statement->bindValue($param, $value, $type);
    }
    
    // Execute the prepared statement.
    $result = $statement->execute();

  }

  public function getDatabase(): SQLite3 {

    return $this->database;

  }
  
}

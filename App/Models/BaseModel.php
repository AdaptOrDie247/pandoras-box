<?php

namespace App\Models;

use SQLite3;

class BaseModel {

  private $database;

  public function __construct(string $db_filepath) {

    $this->database = new SQLite3($db_filepath);

  }

  public function runMigrations(array $migrations): void {

    foreach ($migrations as $migration) {
      $sql = $migration->sql;
      $this->database->exec($sql);
    }

  }

  public function saveEntity(object $entity, object $model, object $connector): void {

    // Set the required variables.
    $database_table_name      = $model->table_name;
    $entity_database_map      = $connector->entity_database_map;
    $database_fields          = array_values($entity_database_map);
    $last_database_field      = end($database_fields); reset($database_fields);
    $database_field_types     = $model->field_types;

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
    foreach ($entity_database_map as $entity_property => $database_field) {
      $param  = ":{$database_field}";
      $value  = $entity->$entity_property->value;
      $type   = constant('SQLITE3_' . $database_field_types[$database_field]);
      $statement->bindValue($param, $value, $type);
    }
    
    // Execute the prepared statement.
    $result = $statement->execute();

  }

}

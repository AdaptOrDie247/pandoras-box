<?php

namespace App\Models;

use SQLite3;

class UniqueVisitor {

  private $database;
  private $table_name = 'unique_visitor';

  public function __construct(string $db_filepath = null) {

    if (!is_null($db_filepath)) {
      $this->database = new SQLite3($db_filepath);
    } else {
      $this->databse  = null;
    }
    
  }

  public function __get(string $name) {

    return $this->$name ?? null;

  }

}

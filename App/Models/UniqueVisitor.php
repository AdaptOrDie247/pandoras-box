<?php

namespace App\Models;

require_once __DIR__ . '/../Entities/UniqueVisitor.php';

use App\Entities\UniqueVisitor as UniqueVisitorEntity;
use SQLite3;

class UniqueVisitor {

  private $database;
  private $table_name = 'unique_visitor';

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

  public function hasUniqueVisitorEntity(UniqueVisitorEntity $unique_visitor): bool {

    return false;

  }

}

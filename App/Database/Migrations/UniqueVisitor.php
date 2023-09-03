<?php

namespace App\Database\Migrations;

class UniqueVisitor {

  private $sql = <<< EOF

  CREATE TABLE IF NOT EXISTS unique_visitor (
  
    remote_address       TEXT    NOT NULL,
    http_user_agent      TEXT    NOT NULL
  
  );
  
  EOF;

  public function __get(string $name) {

    return $this->$name ?? null;

  }

}

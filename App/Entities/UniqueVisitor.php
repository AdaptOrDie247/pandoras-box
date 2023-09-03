<?php

namespace App\Entities;

class UniqueVisitor {

  private $remote_address;
  private $http_user_agent;

  public function __get(string $name) {

    return $this->$name ?? null;

  }

  public function __set(string $name, $value) {

    $this->$name = $value;

  }

}

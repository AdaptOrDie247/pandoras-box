<?php

namespace App\Entities;

class UniqueVisitor {

  private $remote_address;
  private $http_user_agent;

  public function __construct(string $remote_address, string $http_user_agent) {

    $this->remote_address   = $remote_address;
    $this->http_user_agent  = $http_user_agent;

  }

  public function __get(string $name) {

    return $this->$name ?? null;

  }

  public function __set(string $name, $value) {

    $this->$name = $value;

  }

}

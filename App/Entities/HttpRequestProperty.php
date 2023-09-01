<?php

namespace App\Entities;

class HttpRequestProperty {

  private $name;
  private $value;

  public function __construct(string $name, $value) {

    $this->name = $name;
    $this->value = $value;

  }

  public function __get(string $name) {

    return $this->$name ?? null;

  }

  public function __set(string $name, $value) {

    $this->$name = $value;

  }

}

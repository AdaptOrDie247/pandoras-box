<?php

namespace App\Connectors;

class UniqueVisitor {

  private $entity_database_map = [

    'remote_address'      => 'remote_address',
    'http_user_agent'     => 'http_user_agent',

  ];

  public function __get(string $name) {

    return $this->$name ?? null;

  }

}

<?php

namespace App\Models;

class HttpRequest {

  private $table_name = 'http_request';

  private $field_types = [

    'request_time'            => 'TEXT',
    'remote_address'          => 'TEXT',
    'remote_port'             => 'INTEGER',
    'request_uri'             => 'TEXT',
    'request_method'          => 'TEXT',
    'http_host'               => 'TEXT',
    'http_user_agent'         => 'TEXT',
    'http_accept'             => 'TEXT',
    'http_accept_language'    => 'TEXT',
    'http_accept_encoding'    => 'TEXT',

  ];

  public function __get(string $name) {

    return $this->$name ?? null;

  }

}

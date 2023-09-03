<?php

namespace App\Connectors;

class HttpRequest {

  private $entity_database_map = [

    'request_time'            => 'request_time',
    'remote_address'          => 'remote_address',
    'remote_port'             => 'remote_port',
    'request_uri'             => 'request_uri',
    'request_method'          => 'request_method',
    'http_host'               => 'http_host',
    'http_user_agent'         => 'http_user_agent',
    'http_accept'             => 'http_accept',
    'http_accept_language'    => 'http_accept_language',
    'http_accept_encoding'    => 'http_accept_encoding',

  ];

  public function __get(string $name) {

    return $this->$name ?? null;

  }

}

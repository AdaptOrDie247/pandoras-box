<?php

namespace App\Database\Connectors;

class HttpRequest {

  private $database_table_name = 'http_request';

  private $database_field_types = [

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

  public function getDatabaseTableName(): string {

    return $this->database_table_name;

  }

  public function getDatabaseFieldTypes(): array {

    return $this->database_field_types;

  }

  public function getEntityDatabaseMap(): array {

    return $this->entity_database_map;

  }

}

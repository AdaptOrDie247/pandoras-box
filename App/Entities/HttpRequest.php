<?php

namespace App\Entities;

require_once __DIR__ . '/../Entities/Database.php';

use App\Entities\Database;

class HttpRequest {

  private $request_time;
  private $remote_address;
  private $remote_port;
  private $request_uri;
  private $request_method;
  private $http_host;
  private $http_user_agent;
  private $http_accept;
  private $http_accept_language;
  private $http_accept_encoding;

  private const DATABASE_MIGRATION = <<< EOF
    
    CREATE TABLE IF NOT EXISTS http_request (
    
      request_time              INTEGER NOT NULL,
      remote_address            TEXT    NOT NULL,
      remote_port               INTEGER NOT NULL,
      request_uri               TEXT    NOT NULL,
      request_method            TEXT    NOT NULL,
      http_host                 TEXT    NOT NULL,
      http_user_agent           TEXT    NOT NULL,
      http_accept               TEXT    NOT NULL,
      http_accept_language      TEXT    NOT NULL,
      http_accept_encoding      TEXT    NOT NULL
    
    );
    
  EOF;

  private const DATABASE_TABLE_NAME = 'http_request';

  private const DATABASE_FIELD_COLUMN_MAP = [

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

  private const DATABASE_COLUMN_TYPE_MAP = [

    'request_time'            => 'INTEGER',
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

  public function __set(string $name, $value) {

    $this->$name = $value;

  }

  public static function getDatabaseMigration(): string {

    return self::DATABASE_MIGRATION;

  }

  public static function getDatabaseTableName(): string {

    return self::DATABASE_TABLE_NAME;

  }

  public static function getDatabaseFieldColumnMap(): array {

    return self::DATABASE_FIELD_COLUMN_MAP;

  }

  public static function getDatabaseColumnTypeMap(): array {

    return self::DATABASE_COLUMN_TYPE_MAP;

  }

  public function populateFromServerVar(array $server): void {

    $this->request_time           = $server['REQUEST_TIME'];
    $this->remote_address         = $server['REMOTE_ADDR'];
    $this->remote_port            = $server['REMOTE_PORT'];
    $this->request_uri            = $server['REQUEST_URI'];
    $this->request_method         = $server['REQUEST_METHOD'];
    $this->http_host              = $server['HTTP_HOST'];
    $this->http_user_agent        = $server['HTTP_USER_AGENT'];
    $this->http_accept            = $server['HTTP_ACCEPT'];
    $this->http_accept_language   = $server['HTTP_ACCEPT_LANGUAGE'];
    $this->http_accept_encoding   = $server['HTTP_ACCEPT_ENCODING'];

  }

  public function saveToDatabase(Database $database): void {

    $database->saveEntity($this);

  }

}

<?php

namespace App\Database\Migrations;

class HttpRequest {

  private $sql = <<< EOF

  CREATE TABLE IF NOT EXISTS http_request (
  
    request_time              TEXT    NOT NULL,
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

  public function getSql(): string {

    return $this->sql;

  }
  
}

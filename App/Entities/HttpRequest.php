<?php

namespace App\Entities;

use DateTime;
use DateTimeZone;

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

  public function __get(string $name) {

    return $this->$name ?? null;

  }

  public function __set(string $name, $value) {

    $this->$name = $value;

  }

  public function populateInfoFromServerVar(array $server, DateTimeZone $local_time_zone): void {

    // Convert request time to local time as ISO8601 string for easy reading and SQLite3 storage.
    $iso8601_request_time = (new DateTime("@{$server['REQUEST_TIME']}"))->setTimeZone($local_time_zone)->format('c');

    $this->request_time           = $iso8601_request_time;
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

  private function getDateTimeString(int $timestamp): string {

    $datetime = new DateTime('@' . $timestamp);
    return $datetime->format('Y-m-d H:i:s T');

  }

  public function getInfoTextString(): string {

    $info = '';
    
    $info .= sprintf("%- 25s %s\n", "Request Time:",          $this->getDateTimeString($this->request_time));
    $info .= sprintf("%- 25s %s\n", "Remote Address:",        $this->remote_address);
    $info .= sprintf("%- 25s %s\n", "Remote Port:",           $this->remote_port);
    $info .= sprintf("%- 25s %s\n", "Request URI:",           $this->request_uri);
    $info .= sprintf("%- 25s %s\n", "Request Method:",        $this->request_method);
    $info .= sprintf("%- 25s %s\n", "HTTP Host:",             $this->http_host);
    $info .= sprintf("%- 25s %s\n", "HTTP User Agent:",       $this->http_user_agent);
    $info .= sprintf("%- 25s %s\n", "HTTP Accept:",           $this->http_accept);
    $info .= sprintf("%- 25s %s\n", "HTTP Accept Language:",  $this->http_accept_language);
    $info .= sprintf("%- 25s %s\n", "HTTP Accept Encoding:",  $this->http_accept_encoding);

    $info .= PHP_EOL;

    return $info;

  }
  
  public function logInfoStringToTextFile(string $info, string $log_directory): void {

    $current_date   = new DateTime();
    $log_filename   = 'request-log-' . $current_date->format('Y-m-d') . '.log';
    $log_filepath   = "$log_directory/$log_filename";

    $log_filehandle = fopen($log_filepath, 'a') or die('Unable to open file!');
    fwrite($log_filehandle, $info);
    fclose($log_filehandle);

  }

}

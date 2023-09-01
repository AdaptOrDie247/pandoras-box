<?php

namespace App\Entities;

require_once 'HttpRequestProperty.php';

use App\Entities\HttpRequestProperty;
use DateTime;

class HttpRequest {

  private $request_time;
  private $remote_addr;
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

  public function populateInfoFromServerVar(array $server): void {

    $this->request_time           = new HttpRequestProperty('Request Time',           $server['REQUEST_TIME']);
    $this->remote_addr            = new HttpRequestProperty('Remote Address',         $server['REMOTE_ADDR']);
    $this->remote_port            = new HttpRequestProperty('Remote Port',            $server['REMOTE_PORT']);
    $this->request_uri            = new HttpRequestProperty('Request URI',            $server['REQUEST_URI']);
    $this->request_method         = new HttpRequestProperty('Request Method',         $server['REQUEST_METHOD']);
    $this->http_host              = new HttpRequestProperty('HTTP Host',              $server['HTTP_HOST']);
    $this->http_user_agent        = new HttpRequestProperty('HTTP User Agent',        $server['HTTP_USER_AGENT']);
    $this->http_accept            = new HttpRequestProperty('HTTP Accept',            $server['HTTP_ACCEPT']);
    $this->http_accept_language   = new HttpRequestProperty('HTTP Accept Language',   $server['HTTP_ACCEPT_LANGUAGE']);
    $this->http_accept_encoding   = new HttpRequestProperty('HTTP Accept Encoding',   $server['HTTP_ACCEPT_ENCODING']);

  }

  private function getDateTimeString(int $timestamp): string {

    $datetime = new DateTime('@' . $timestamp);
    return $datetime->format('Y-m-d H:i:s T');

  }

  public function getInfoTextString(): string {

    $info = '';
    
    $info .= sprintf("%- 25s %s\n", "{$this->request_time->name}:",          $this->getDateTimeString($this->request_time->value));
    $info .= sprintf("%- 25s %s\n", "{$this->remote_addr->name}:",           $this->remote_addr->value);
    $info .= sprintf("%- 25s %s\n", "{$this->remote_port->name}:",           $this->remote_port->value);
    $info .= sprintf("%- 25s %s\n", "{$this->request_uri->name}:",           $this->request_uri->value);
    $info .= sprintf("%- 25s %s\n", "{$this->request_method->name}:",        $this->request_method->value);
    $info .= sprintf("%- 25s %s\n", "{$this->http_host->name}:",             $this->http_host->value);
    $info .= sprintf("%- 25s %s\n", "{$this->http_user_agent->name}:",       $this->http_user_agent->value);
    $info .= sprintf("%- 25s %s\n", "{$this->http_accept->name}:",           $this->http_accept->value);
    $info .= sprintf("%- 25s %s\n", "{$this->http_accept_language->name}:",  $this->http_accept_language->value);
    $info .= sprintf("%- 25s %s\n", "{$this->http_accept_encoding->name}:",  $this->http_accept_encoding->value);

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

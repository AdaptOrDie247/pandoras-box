#!/usr/bin/env php
<?php

// Load Composer dependencies.
require_once __DIR__ . '/../vendor/autoload.php';

// Load Shodan PHP REST API unmanaged dependency.
require_once __DIR__ . '/../vendor-unmanaged/shodan-php-rest-api/src/Shodan.php';

// Load the dotenv file.
Dotenv\Dotenv::createImmutable(__DIR__ . '/../')->load();

// Initialize Shodan API.
$shodan_api_key = $_ENV['shodan.api_key'];
$shodan_api     = new Shodan($shodan_api_key, TRUE);

// Get IP address argument, and print usage if missing.
$ip_address = $argv[1] ?? null;
if (is_null($ip_address)) {
  echo "Usage: {$argv[0]} IP_ADDRESS";
  exit(1);
}

// Get results from Shodan API.
$shodan_ip_info = json_decode(json_encode($shodan_api->ShodanHost(['ip' => $ip_address]), JSON_PRETTY_PRINT));

// Format missing values from API results.
$ip_info['city']           = $shodan_ip_info->city             ??  'Not Specified';
$ip_info['region_code']    = $shodan_ip_info->region_code      ??  'Not Specified';
$ip_info['country_name']   = $shodan_ip_info->country_name     ??  'Not Specified';
$ip_info['area_code']      = $shodan_ip_info->area_code        ??  'Not Specified';
$ip_info['org']            = $shodan_ip_info->org              ??  'Not Specified';
$ip_info['isp']            = $shodan_ip_info->isp              ??  'Not Specified';
$ip_info['asn']            = $shodan_ip_info->asn              ??  'Not Specified';
$ip_info['domains']        = empty($shodan_ip_info->domains)   ?   'Not Specified'   : implode(', ', $shodan_ip_info->domains);
$ip_info['hostnames']      = empty($shodan_ip_info->hostnames) ?   'Not Specified'   : implode(', ', $shodan_ip_info->hostnames);
$ip_info['os']             = $shodan_ip_info->os               ??  'Not Specified';
$ip_info['ports']          = empty($shodan_ip_info->ports)     ?   'Not Specified'   : implode(', ', $shodan_ip_info->ports);

// Print output.
printf("%-25s %s\n",  'IP Address:',        $ip_address);
printf("%-25s %s\n",  'Location:',          "{$ip_info['city']}, {$ip_info['region_code']}, {$ip_info['country_name']}");
printf("%-25s %s\n",  'Area Code:',         $ip_info['area_code']);
printf("%-25s %s\n",  'Organization:',      $ip_info['org']);
printf("%-25s %s\n",  'ISP:',               $ip_info['isp']);
printf("%-25s %s\n",  'ASN:',               $ip_info['asn']);
printf("%-25s %s\n",  'Domains:',           $ip_info['domains']);
printf("%-25s %s\n",  'Hostnames:',         $ip_info['hostnames']);
printf("%-25s %s\n",  'Operating System:',  $ip_info['os']);
printf("%-25s %s\n",  'Open Ports:',        $ip_info['ports']);
echo PHP_EOL;

<?php
defined('OPEN_SUPPORT_INIT') or exit('Access denied');

return array (
  'db' => 
  array (
    'host' => 'localhost',
    'port' => 3306,
    'name' => 'opensupport',
    'user' => 'root',
    'pass' => 'root',
  ),
  'app' => 
  array (
    'domain' => 'localhost',
    'url' => 'http://localhost/opensupport',
    'timezone' => 'Europe/Paris',
    'max_file_size' => 10,
    'max_files' => 5,
    'host_info' => 
    array (
      'provider' => 'Planethoster',
      'details' => '',
    ),
  ),
);

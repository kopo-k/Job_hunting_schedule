<?php
/**
 * The development database settings. These get merged with the global settings.
 */

 return [
  'default' => [
    'type'        => 'mysqli',
    'connection'  => [
      'hostname'   => 'db',         // docker-compose のサービス名
      'port'       => '3306',
      'database'   => 'fuelphp',
      'username'   => 'root',
      'password'   => 'root',
      'persistent' => false,
    ],
    'identifier'  => '`',
    'table_prefix'=> '',
    'charset'     => 'utf8mb4',
    'enable_cache'=> false,
    'profiling'   => true,
  ],
];

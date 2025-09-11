<?php
return [
  'default' => [
    'type'       => 'pdo',
    'connection' => [
      'dsn'        => 'mysql:host=db;dbname=fuelphp;charset=utf8mb4',
      'username'   => 'root',
      'password'   => 'root',
      'persistent' => false,
    ],
    'identifier'   => '`',
    'table_prefix' => '',
    'charset'      => 'utf8mb4',
    'enable_cache' => false,
    'profiling'    => true,   // 開発中は true にしてSQLを確認しやすく
  ],
];
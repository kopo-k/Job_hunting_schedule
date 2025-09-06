<?php
return [
  'default' => [
    'type' => 'mysqli',
    'connection' => [
      'hostname'   => getenv('DB_HOST') ?: 'db',       // ← ここがポイント！
      'database'   => getenv('DB_NAME') ?: 'fuelphp',
      'username'   => getenv('DB_USER') ?: 'appuser',
      'password'   => getenv('DB_PASSWORD') ?: 'secret',
      'persistent' => false,
      'port'       => 3306,
    ],
    'charset'   => 'utf8mb4',
  ],
];

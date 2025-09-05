<?php
// fuel/app/config/db.php
return [
  'default' => [
    'type' => 'mysqli',
    'connection' => [
      'hostname'   => getenv('DB_HOST') ?: '127.0.0.1',
      'database'   => getenv('DB_NAME') ?: 'fuelphp',
      'username'   => getenv('DB_USER') ?: 'appuser',
      'password'   => getenv('DB_PASSWORD') ?: 'secret',
      'persistent' => false,
    ],
    'charset'   => 'utf8mb4',
    'profiling' => false,
  ],
];

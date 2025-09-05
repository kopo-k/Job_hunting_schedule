<?php
return [
  'default' => [
    'type' => 'mysqli',
    'connection' => [
      'hostname'   => getenv('DB_HOST') ?: 'db',       // ← compose: DB_HOST=db
      'database'   => getenv('DB_NAME') ?: 'fuelphp',  // ← fuelphp
      'username'   => getenv('DB_USER') ?: 'appuser',  // ← appuser
      'password'   => getenv('DB_PASSWORD') ?: 'secret', // ← secret
      'persistent' => false,
    ],
    'charset'   => 'utf8mb4',
  ],
];

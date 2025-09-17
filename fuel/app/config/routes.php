<?php
return [
  '_root_' => 'kanban/index',
  'signup' => 'auth/signup',   
  'login'  => 'auth/login',
  'logout' => 'auth/logout',

    // REST API（標準パターン）
    'statuses'                => 'statuses/index',        // GET
    'companies'               => 'companies/index',       // GET/POST
    'companies/(:num)'        => 'companies/item/$1',     // GET/PUT/DELETE
    'companies/(:num)/status' => 'companies/status/$1',   // PUT（列移動）
];
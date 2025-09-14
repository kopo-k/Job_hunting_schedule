<?php
return [
  '_root_' => 'kanban/index',
  'signup' => 'auth/signup',   
  'login'  => 'auth/login',
  'logout' => 'auth/logout',

    // REST API（CRUD & D&D 専用）
    'api/statuses'                => 'api/statuses/index',        // GET
    'api/companies'               => 'api/companies/index',       // GET/POST
    'api/companies/(:num)'        => 'api/companies/item/$1',     // GET/PUT/DELETE
    'api/companies/(:num)/status' => 'api/companies/status/$1',   // PUT（列移動）
];
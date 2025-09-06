<?php
return [
  '_root_' => 'applications/index',
  'applications' => 'applications/index',

  // Knockout 用 API
  'api/statuses'           => 'api/statuses/index',
  'api/companies'          => 'api/companies/index',
  'api/companies/(:num)'   => 'api/companies/view/$1',
  'api/schedules'          => 'api/schedules/index',

  // ★ ToDo API（追加）
  'api/todos'              => 'api/todos/index',
  'api/todos/(:num)'       => 'api/todos/view/$1',
];

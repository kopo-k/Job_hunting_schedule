<?php
return [
  '_root_' => 'applications/index',
  'applications' => 'applications/index',

  // Knockout 用 API
  'api/statuses'           => 'api/statuses/index',
  'api/companies'          => 'api/companies/index',
  'api/companies/(:num)'   => 'api/companies/view/$1',
  'api/schedules'          => 'api/schedules/index',
];

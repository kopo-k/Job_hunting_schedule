<?php
return [
  'driver'          => 'cookie',   // まずは cookie
  'cookie_name'     => 'sess_sched',
  'encrypt_cookie'  => true,       // cookie暗号化（後述の crypt.php が必要）
  'expire_on_close' => false,
  'expiration_time' => 7200,       // 2時間
  'http_only'       => true,      //JavaScript から読み取れなくするため
  'same_site'       => 'Lax',
];

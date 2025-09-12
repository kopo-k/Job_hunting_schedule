<?php
return [
  // ルートURL（ApacheのDocumentRootを project/public に向ける前提）
  'base_url'   => '/',
  // index.php をURLに出さない
  'index_file' => false,

  // 日本向けの既定
  'language' => 'ja',
  'locale'   => 'ja_JP.UTF-8',
  'timezone' => 'Asia/Tokyo',
  'encoding' => 'UTF-8',

  // 出力フィルタ（基本XSS対策）
  'security' => [
    'csrf_autoload' => false, // 一時的に無効化してテスト
    'token_salt'    => 'd6a1946b75f0e90d323f746a5d2ff95d3bfe069a099733315a3f49c74de85b75',
    'uri_filter'    => ['htmlentities'],
    'output_filter' => ['Security::htmlentities'],
  ],

  // Cookieの既定（セッション設定にも関わる）
  'cookie' => [
    'http_only'  => true, //JavaScript（document.cookie）から Cookie が読めなくさせるため
    'secure'     => false,   // 本番HTTPSにしたら true に
    'same_site'  => 'Lax',   // CSRF軽減
    'expiration' => 0,       // ブラウザ閉じても残すなら秒指定
  ],

  // 開発中はプロファイラONが便利（database.php の profiling と合わせる）
  'profiling' => true,
];

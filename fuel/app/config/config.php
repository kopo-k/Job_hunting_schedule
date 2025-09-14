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

  // 出力フィルタ（基本XSS対策）（要件13：セキュリティ）
  'security' => [
    'csrf_autoload' => false, // CSRF保護を一時的に無効化（API動作確認用）
    'token_salt'    => 'd6a1946b75f0e90d323f746a5d2ff95d3bfe069a099733315a3f49c74de85b75',
    'uri_filter'    => ['htmlentities'],
    'output_filter' => ['Security::htmlentities'],
    'csrf_expiration' => 7200, // CSRFトークンの有効期限（2時間）
    'csrf_token_key' => '__fuel_csrf_token__',
    'whitelisted_keys' => [],  // XSS対策で除外するキー
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
  
  // カスタム設定（要件3：config設定のカスタマイズ）
  'custom' => [
    // アプリケーション固有設定
    'app_name' => '就活スケジューラー',
    'app_version' => '1.0.0',
    'contact_email' => 'admin@jobhunting.local',
    
    // 企業管理設定
    'max_companies_per_user' => 100,
    'default_status' => 'consider',
    'allowed_employment_types' => ['正社員', '契約社員', 'インターン', 'アルバイト'],
    
    // ファイルアップロード設定
    'upload_max_size' => 5 * 1024 * 1024, // 5MB
    'allowed_extensions' => ['pdf', 'doc', 'docx'],
    
    // Pagination設定
    'items_per_page' => 20,
    
    // デバッグ設定
    'debug_mode' => true,
    'log_level' => 'DEBUG',
  ],
];

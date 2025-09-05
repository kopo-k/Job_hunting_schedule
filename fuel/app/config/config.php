<?php
return [
  'base_url' => '/',
  'language' => 'ja',
  'security' => [
		// XSSの対策のため自動的にエスケープ
    'output_filter' => [], 
		//CSRF対策のため自動的にCSRFトークン生成
    'csrf_autoload' => true,                      
  ],
];

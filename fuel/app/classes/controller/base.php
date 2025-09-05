<?php
// fuel/app/classes/controller/base.php

class Controller_Base extends \Controller
{
    public function before()
    {
        parent::before();

        // セッション & Cookie
        if (\Session::get('user_id') === null) \Session::set('user_id', 1);
        if (\Cookie::get('theme') === null) \Cookie::set('theme', 'light');

        // View 共有
        \View::set_global('csrf_token', \Security::fetch_token());
        \View::set_global('current_user', \Session::get('user_id'));

        // 自動出力フィルタはOFF（NULLでWarning回避）
        \Config::set('security.output_filter', []);
    }

    // ← ここでレスポンスに対してヘッダを設定する
    public function after($response)
    {
        // $response が null の場合は既存の response か新規を使う
        if ($response === null) {
            $response = $this->response ?: \Response::forge();
        }

        // 非静的にヘッダを設定（Deprecated回避）
        $response->set_header('X-Frame-Options', 'DENY');
        $response->set_header('X-Content-Type-Options', 'nosniff');
        $response->set_header('Referrer-Policy', 'strict-origin-when-cross-origin');
        $response->set_header(
            'Content-Security-Policy',
            "default-src 'self'; img-src 'self' data:; script-src 'self'; style-src 'self' 'unsafe-inline'"
        );

        return parent::after($response);
    }
}

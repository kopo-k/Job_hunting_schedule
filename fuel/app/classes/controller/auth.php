<?php
// fuel/app/classes/controller/auth.php
class Controller_Auth extends \Controller
{
    // ログイン画面 & 処理
    public function action_login()
    {
        // 既ログインならトップへ
        if (\Session::get('user_id')) {
            return \Response::redirect('/');
        }

        if (\Input::method() === 'POST') {
            // CSRF チェック
            $token = \Input::post('fuel_csrf_token');
            if (!$token || !\Security::check_token($token)) {
                \Session::set_flash('error', 'CSRFトークンが無効です。もう一度お試しください。');
                return \Response::redirect('login');
            }

            $email = trim((string)\Input::post('email'));
            $pass  = (string)\Input::post('password');

            $user = \DB::select()->from('users')->where('email', $email)->execute()->current();
            if ($user && password_verify($pass, $user['password'])) {
                // セッション固定化対策
                \Session::instance()->rotate();
                \Session::set('user_id', (int)$user['id']);
                return \Response::redirect('/');
            }
            \Session::set_flash('error', 'IDかパスワードが違います。');
            return \Response::redirect('login');
        }

        return \View::forge('auth/login');
    }

    // サインアップ画面 & 処理
    public function action_signup()
    {
        if (\Session::get('user_id')) {
            return \Response::redirect('/');
        }

        if (\Input::method() === 'POST') {
            // CSRF チェック
            $token = \Input::post('fuel_csrf_token');
            if (!$token || !\Security::check_token($token)) {
                \Session::set_flash('error', 'CSRFトークンが無効です。もう一度お試しください。');
                return \Response::redirect('signup');
            }

            // 入力バリデーション
            $v = \Validation::forge();
            $v->add('name', '名前')->add_rule('required')->add_rule('max_length', 50);
            $v->add('email', 'メール')->add_rule('required')->add_rule('valid_email')->add_rule('max_length', 255);
            $v->add('password', 'パスワード')->add_rule('required')->add_rule('min_length', 8);

            if (!$v->run()) {
                \Session::set_flash('error', '入力に誤りがあります。');
                return \Response::redirect('signup');
            }

            $name  = $v->validated('name');
            $email = $v->validated('email');
            $pass  = $v->validated('password');

            // メール重複チェック
            $exists = \DB::select()->from('users')->where('email', $email)->execute()->current();
            if ($exists) {
                \Session::set_flash('error', 'このメールは既に使われています。');
                return \Response::redirect('signup');
            }

            // 登録
            $hash = password_hash($pass, PASSWORD_DEFAULT);
            list($id,) = \DB::insert('users')->set([
                'name'     => $name,
                'email'    => $email,
                'password' => $hash,
            ])->execute();

            \Session::instance()->rotate();
            \Session::set('user_id', (int)$id);
            return \Response::redirect('/');
        }

        return \View::forge('auth/signup');
    }

    // ログアウト
    public function action_logout()
    {
        \Session::delete('user_id');
        // 必要なら全破棄：\Session::destroy();
        return \Response::redirect('login');
    }
}

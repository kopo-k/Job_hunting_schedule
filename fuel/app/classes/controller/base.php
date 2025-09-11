<?php
// fuel/app/classes/controller/base.php
//ページ表示の前処理
class Controller_Base extends \Controller
{
    protected $current_user = null;

    public function before()
    {
        parent::before();

        $uid = \Session::get('user_id');
        if (!$uid) {
            // APIなら401、画面ならログインへ
            if (\Input::is_ajax() || strpos(\Input::uri(), 'api/') === 0) {
                return \Response::forge(json_encode(['error'=>'unauthorized']), 401, ['Content-Type'=>'application/json']);
            }
            \Response::redirect('login');
        }
        $this->current_user = (int)$uid;
    }
}

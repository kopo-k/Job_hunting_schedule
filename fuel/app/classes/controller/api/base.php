<?php
// fuel/app/classes/controller/api/base.php
// API全体の共通ガード（認可＋CSRF）
class Controller_Api_Base extends \Controller_Rest
{
    protected $format = 'json';
    protected $current_user = null;

    public function before()
    {
        parent::before();

        // 1) 認可：未ログインなら 401
        $uid = \Session::get('user_id');
        if (!$uid) {
            return $this->response(['error' => 'unauthorized'], 401);
        }
        $this->current_user = (int) $uid;

        // 2) CSRF：GET/HEAD/OPTIONS 以外はトークン必須
        $method = \Input::method();
        if (!in_array($method, ['GET', 'HEAD', 'OPTIONS'], true)) {
            $token = \Input::headers('X-CSRF-Token') ?: \Input::param('fuel_csrf_token');
            if (!$token || !\Security::check_token($token)) {
                return $this->response(['error' => 'bad csrf'], 400);
            }
        }
    }
}

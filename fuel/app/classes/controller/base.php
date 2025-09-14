<?php

use Fuel\Core\Controller;
use Fuel\Core\Session;
use Fuel\Core\Response;

class Controller_Base extends Controller
{
    protected $current_user = null;
    
    public function before()
    {
        parent::before();
        
        // セッション開始
        try {
            Session::create();
        } catch (\Exception $e) {
            // セッションが既に開始されている場合は無視
        }
        
        // 認証が必要なページかチェック
        if ($this->needs_auth()) {
            $user_id = Session::get('user_id');
            
            if ($user_id) {
                try {
                    // ユーザー情報を取得（簡易的にセッションのIDをそのまま使用）
                    $this->current_user = $user_id;
                } catch (Exception $e) {
                    Session::delete('user_id');
                    Response::redirect('auth/login');
                }
            } else {
                Response::redirect('auth/login');
            }
        }
    }
    
    /**
     * 認証が必要かどうかを判定する
     * サブクラスでオーバーライドして使用
     */
    protected function needs_auth()
    {
        return true; // デフォルトは認証必須
    }
}
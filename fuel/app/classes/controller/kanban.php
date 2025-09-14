<?php

/**
 * Kanbanコントローラ（要件5：名前空間使用）
 */
class Controller_Kanban extends Controller_Base
{
    public function action_index()
    {
        // Controller_Base::before()で認証チェック済み
        // $this->current_userに現在のユーザーIDが設定される
        
        // ログイン状態をビューに渡す
        $user_id = \Session::get('user_id');
        $user_name = 'ゲストユーザー';
        
        if ($user_id) {
            try {
                $user = \DB::select('name')->from('users')->where('id', $user_id)->execute()->current();
                if ($user) {
                    $user_name = $user['name'];
                }
            } catch (\Exception $e) {
                // エラーの場合はデフォルト名を使用
            }
        }
        
        $data = array(
            'is_logged_in' => (bool)$user_id,
            'user_name' => $user_name
        );
        
        return \View::forge('kanban/index', $data);
    }
}
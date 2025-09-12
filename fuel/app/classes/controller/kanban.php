<?php
class Controller_Kanban extends \Controller
{
    public function action_index()
    {
        // セッション開始（FuelPHPの正しい方法）
        try {
            \Session::create();
        } catch (\Exception $e) {
            // セッションが既に開始されている場合は無視
        }
        
        // テスト用ユーザーIDを設定
        if (!\Session::get('user_id')) {
            \Session::set('user_id', 1);
        }
        
        return \View::forge('kanban/index');
    }
}
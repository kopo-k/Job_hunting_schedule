<?php

class Controller_Api_Companies extends \Controller_Rest
{
    protected $format = 'json';
    
    public function before()
    {
        parent::before();
        
        // セッション開始
        try {
            \Session::create();
        } catch (\Exception $e) {
            // セッションが既に開始されている場合は無視
        }
        
        // テスト用ユーザーIDを設定
        if (!\Session::get('user_id')) {
            \Session::set('user_id', 1);
        }
    }
    
    // GET /api/companies - 企業一覧取得
    public function get_index()
    {
        try {
            $user_id = \Session::get('user_id');
            
            // 仮のデータを返す（後でDB接続に変更）
            $companies = array(
                array(
                    'id' => 1,
                    'name' => 'サンプル企業1',
                    'key' => 'consider',
                    'position_title' => 'エンジニア',
                    'website_url' => 'https://example.com',
                    'employment_type' => '正社員',
                    'location_text' => '東京都',
                    'description' => 'テスト企業です'
                )
            );
            
            return $this->response($companies);
        } catch (\Exception $e) {
            return $this->response(array('error' => $e->getMessage()), 500);
        }
    }
    
    // POST /api/companies - 企業追加
    public function post_index()
    {
        try {
            $user_id = \Session::get('user_id');
            $input = \Input::json() ?: array();
            
            // バリデーション
            if (empty($input['name'])) {
                return $this->response(array('error' => '企業名は必須です'), 400);
            }
            
            // 仮の成功レスポンス（後でDB保存に変更）
            $new_id = rand(1000, 9999); // 仮のID生成
            
            return $this->response(array(
                'id' => $new_id,
                'message' => '企業を追加しました'
            ), 201);
            
        } catch (\Exception $e) {
            return $this->response(array('error' => $e->getMessage()), 500);
        }
    }
    
    // PUT /api/companies/{id} - 企業更新
    public function put_item($id)
    {
        try {
            $user_id = \Session::get('user_id');
            $input = \Input::json() ?: array();
            
            // 仮の成功レスポンス
            return $this->response(array(
                'id' => (int)$id,
                'message' => '企業を更新しました'
            ));
            
        } catch (\Exception $e) {
            return $this->response(array('error' => $e->getMessage()), 500);
        }
    }
    
    // DELETE /api/companies/{id} - 企業削除
    public function delete_item($id)
    {
        try {
            $user_id = \Session::get('user_id');
            
            // 仮の成功レスポンス
            return $this->response(array(
                'id' => (int)$id,
                'message' => '企業を削除しました'
            ));
            
        } catch (\Exception $e) {
            return $this->response(array('error' => $e->getMessage()), 500);
        }
    }
    
    // PUT /api/companies/{id}/status - ステータス更新（D&D用）
    public function put_status($id)
    {
        try {
            $user_id = \Session::get('user_id');
            $input = \Input::json() ?: array();
            
            // 仮の成功レスポンス
            return $this->response(array(
                'id' => (int)$id,
                'status_key' => $input['status_key'] ?? 'consider',
                'message' => 'ステータスを更新しました'
            ));
            
        } catch (\Exception $e) {
            return $this->response(array('error' => $e->getMessage()), 500);
        }
    }
}
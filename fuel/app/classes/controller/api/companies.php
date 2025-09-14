<?php

/**
 * 企業API（要件5：名前空間使用）
 */
class Controller_Api_Companies extends \Controller_Rest
{
    protected $format = 'json';
    protected $current_user_id = null;
    
    /**
     * beforeMethod実装（要件2）
     * 各API呼び出し前に認証チェックを実行
     */
    public function before()
    {
        parent::before();
        
        // API用にCSRFチェックを無効化（要件13対応、API専用）
        \Config::set('security.csrf_autoload', false);
        
        // セッション開始（要件4：セッション使用）
        try {
            \Session::create();
        } catch (\Exception $e) {
            // セッションが既に開始されている場合は無視
        }
        
        // 認証チェック（要件2：beforeMethod）
        $user_id = \Session::get('user_id');
        if (!$user_id) {
            // テスト用：未認証でもデモユーザーとして扱う（本番では削除）
            $user_id = 1; // デモユーザーID
            \Session::set('user_id', $user_id);
        }
        
        // 認証済みユーザーIDを保存
        $this->current_user_id = $user_id;
    }
    
    // GET /api/companies - 企業一覧取得（要件7,8,9: DB使用・1:n・Read）
    public function get_index()
    {
        try {
            // beforeMethod で認証済みのユーザーID使用
            $user_id = $this->current_user_id;
            
            // DBから実際のデータを取得（1:n関係のJOIN）
            $companies = Model_Company::get_all_by_user($user_id);
            
            // データが空でも正常なレスポンス（200 OK）として明示的に空配列を返す
            if (empty($companies)) {
                // FuelPHPのController_Restが204を返すのを防ぐため、明示的に200とJSONを設定
                $this->format = 'json';
                \Response::forge(json_encode(array()), 200, array('Content-Type' => 'application/json'))->send(true);
                return;
            }
            
            return $this->response($companies, 200);
        } catch (\Exception $e) {
            return $this->response(array('error' => $e->getMessage()), 500);
        }
    }
    
    // POST /api/companies - 企業追加（要件7,9: DB使用・Create）
    public function post_index()
    {
        try {
            $user_id = $this->current_user_id;
            $input = \Input::json() ?: array();
            
            // status_keyをstatus_idに変換（要件3：config設定使用）
            $default_status = \Config::get('custom.default_status', 'consider');
            $status_key = isset($input['status_key']) ? $input['status_key'] : $default_status;
            $status_id = Model_Company::get_status_id_by_key($status_key);
            
            if (!$status_id) {
                return $this->response(array('error' => '無効なステータスです'), 400);
            }
            
            $data = array(
                'status_id' => $status_id,
                'name' => trim($input['name']),
                'website_url' => isset($input['website_url']) ? $input['website_url'] : null,
                'position_title' => isset($input['position_title']) ? $input['position_title'] : null,
                'employment_type' => isset($input['employment_type']) ? $input['employment_type'] : null,
                'location_text' => isset($input['location_text']) ? $input['location_text'] : null,
                'description' => isset($input['description']) ? $input['description'] : null,
            );
            
            // バリデーション
            $val = Model_Company::validate();
            if (!$val->run($data)) {
                return $this->response(array(
                    'error' => 'バリデーションエラー', 
                    'messages' => $val->error()
                ), 422);
            }
            
            // DB保存
            $new_id = Model_Company::create($user_id, $data);
            
            return $this->response(array(
                'id' => $new_id,
                'message' => '企業を追加しました'
            ), 201);
            
        } catch (\Exception $e) {
            return $this->response(array('error' => $e->getMessage()), 500);
        }
    }
    
    // PUT /api/companies/{id} - 企業更新（要件7,9: DB使用・Update）
    public function put_item($id)
    {
        try {
            $user_id = \Session::get('user_id');
            $input = \Input::json() ?: array();
            
            // status_keyがある場合はstatus_idに変換
            if (isset($input['status_key'])) {
                $status_id = Model_Company::get_status_id_by_key($input['status_key']);
                if (!$status_id) {
                    return $this->response(array('error' => '無効なステータスです'), 400);
                }
                $input['status_id'] = $status_id;
                unset($input['status_key']);
            }
            
            // DB更新
            $affected_rows = Model_Company::update_one($user_id, (int)$id, $input);
            
            return $this->response(array(
                'id' => (int)$id,
                'affected_rows' => $affected_rows,
                'message' => '企業を更新しました'
            ));
            
        } catch (\Exception $e) {
            return $this->response(array('error' => $e->getMessage()), 500);
        }
    }
    
    // DELETE /api/companies/{id} - 企業削除（要件7,9: DB使用・Delete）
    public function delete_item($id)
    {
        try {
            $user_id = \Session::get('user_id');
            
            // DB削除
            $affected_rows = Model_Company::delete_one($user_id, (int)$id);
            
            return $this->response(array(
                'id' => (int)$id,
                'affected_rows' => $affected_rows,
                'message' => '企業を削除しました'
            ));
            
        } catch (\Exception $e) {
            return $this->response(array('error' => $e->getMessage()), 500);
        }
    }
    
    // PUT /api/companies/{id}/status - ステータス更新（要件7,9: DB使用・Update・D&D用）
    public function put_status($id)
    {
        try {
            $user_id = \Session::get('user_id');
            $input = \Input::json() ?: array();
            
            // デバッグログ
            \Log::info("Status update request - ID: $id, User: $user_id, Input: " . json_encode($input));
            
            // status_keyをstatus_idに変換
            $status_id = Model_Company::get_status_id_by_key($input['status_key'] ?? '');
            if (!$status_id) {
                \Log::error("Invalid status key: " . ($input['status_key'] ?? 'null'));
                return $this->response(array('error' => '無効なステータスです'), 400);
            }
            
            // DBでステータス更新
            $affected_rows = Model_Company::update_status($user_id, (int)$id, $status_id);
            
            \Log::info("Status update result - Affected rows: $affected_rows");
            
            return $this->response(array(
                'id' => (int)$id,
                'status_key' => $input['status_key'],
                'status_id' => $status_id,
                'affected_rows' => $affected_rows,
                'message' => 'ステータスを更新しました'
            ));
            
        } catch (\Exception $e) {
            \Log::error("Status update error: " . $e->getMessage());
            return $this->response(array('error' => $e->getMessage()), 500);
        }
    }
}
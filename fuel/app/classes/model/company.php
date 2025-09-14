<?php
use Fuel\Core\Validation;
use Fuel\Core\DB;
/**
 * 企業モデル（要件5：名前空間、要件7: DBクラス使用, 要件8: 1:n関係, 要件9: CRUD）
 */
class Model_Company extends \Fuel\Core\Model
{
    /**
     * バリデーションルール
     */
    public static function validate()
    {
        $val = Validation::forge();
        $val->add('name', '企業名')
            ->add_rule('required')
            ->add_rule('max_length', 100);
        $val->add('status_id', 'ステータス')
            ->add_rule('required')
            ->add_rule('valid_string', 'numeric');
        $val->add('website_url', 'URL')
            ->add_rule('max_length', 255);
        $val->add('position_title', '職種')
            ->add_rule('max_length', 100);
        $val->add('employment_type', '雇用形態')
            ->add_rule('max_length', 30);
        $val->add('location_text', '勤務地')
            ->add_rule('max_length', 100);
        
        return $val;
    }
    
    /**
     * 指定ユーザーの企業一覧取得（ステータス情報付き）
     * 要件8: 1:n関係のJOIN処理
     */
    public static function get_all_by_user($user_id)
    {
        return DB::select('c.*', 's.key', 's.label_ja', 's.color_hex')
            ->from(array('companies', 'c'))
            ->join(array('statuses', 's'), 'INNER')
            ->on('c.status_id', '=', 's.id')
            ->where('c.user_id', $user_id)
            ->order_by('c.id', 'ASC')
            ->execute()
            ->as_array();
    }
    
    /**
     * 企業作成（要件9: Create）
     */
    public static function create($user_id, array $data)
    {
        $insert_data = array(
            'user_id' => (int)$user_id,
            'status_id' => (int)$data['status_id'],
            'name' => $data['name'],
            'website_url' => isset($data['website_url']) ? $data['website_url'] : null,
            'position_title' => isset($data['position_title']) ? $data['position_title'] : null,
            'employment_type' => isset($data['employment_type']) ? $data['employment_type'] : null,
            'location_text' => isset($data['location_text']) ? $data['location_text'] : null,
            'description' => isset($data['description']) ? $data['description'] : null,
        );
        
        list($insert_id, $rows_affected) = DB::insert('companies')
            ->set($insert_data)
            ->execute();
            
        return $insert_id;
    }
    
    /**
     * 1件取得（要件9: Read）
     */
    public static function find_one($user_id, $company_id)
    {
        return DB::select()
            ->from('companies')
            ->where('user_id', $user_id)
            ->where('id', $company_id)
            ->execute()
            ->current();
    }
    
    /**
     * 企業更新（要件9: Update）
     */
    public static function update_one($user_id, $company_id, array $data)
    {
        $update_data = array();
        
        // 更新対象フィールドのみセット
        if (isset($data['status_id'])) {
            $update_data['status_id'] = (int)$data['status_id'];
        }
        if (isset($data['name'])) {
            $update_data['name'] = $data['name'];
        }
        if (array_key_exists('website_url', $data)) {
            $update_data['website_url'] = $data['website_url'];
        }
        if (array_key_exists('position_title', $data)) {
            $update_data['position_title'] = $data['position_title'];
        }
        if (array_key_exists('employment_type', $data)) {
            $update_data['employment_type'] = $data['employment_type'];
        }
        if (array_key_exists('location_text', $data)) {
            $update_data['location_text'] = $data['location_text'];
        }
        if (array_key_exists('description', $data)) {
            $update_data['description'] = $data['description'];
        }
        
        if (empty($update_data)) {
            return 0;
        }
        
        return DB::update('companies')
            ->set($update_data)
            ->where('user_id', $user_id)
            ->where('id', $company_id)
            ->execute();
    }
    
    /**
     * ステータスのみ更新（D&D用）
     */
    public static function update_status($user_id, $company_id, $status_id)
    {
        return DB::update('companies')
            ->set(array('status_id' => (int)$status_id))
            ->where('user_id', $user_id)
            ->where('id', $company_id)
            ->execute();
    }
    
    /**
     * 企業削除（要件9: Delete）
     */
    public static function delete_one($user_id, $company_id)
    {
        return DB::delete('companies')
            ->where('user_id', $user_id)
            ->where('id', $company_id)
            ->execute();
    }
    
    /**
     * status_keyからstatus_idを取得
     */
    public static function get_status_id_by_key($key)
    {
        $result = DB::select('id')
            ->from('statuses')
            ->where('key', $key)
            ->execute()
            ->current();
            
        return $result ? (int)$result['id'] : null;
    }
}
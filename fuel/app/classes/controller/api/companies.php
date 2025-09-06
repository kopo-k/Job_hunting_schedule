<?php
class Controller_Api_Companies extends \Controller_Rest
{
    protected $format = 'json';

    public function get_index()
    {
        $uid = \Session::get('user_id');
        $rows = \DB::select()->from('companies')
            ->where('user_id', $uid)
            ->order_by('updated_at','desc')->execute()->as_array();
        return $this->response($rows);
    }

    public function post_index()
    {
        \Security::check_token(); // CSRF
        $uid = \Session::get('user_id');
        $d = \Input::json();

        // Validation
        $v = \Validation::forge();
        $v->add('name','会社名')->add_rule('required')->add_rule('max_length',100);
        $v->add('status_id','ステータス')->add_rule('required')->add_rule('valid_string',['numeric']);
        if (!$v->run($d)) return $this->response(['errors'=>$v->error()], 422);

        try {
            list($id,) = \DB::insert('companies')->set([
                'user_id'        => $uid,
                'name'           => $d['name'],
                'status_id'      => (int)$d['status_id'],
                'website_url'    => $d['website_url'] ?? null,
                'position_title' => $d['position_title'] ?? null,
                'employment_type'=> $d['employment_type'] ?? null,
                'location_text'  => $d['location_text'] ?? null,
                'description'    => $d['description'] ?? null,
            ])->execute();
            return $this->response(['id'=>$id], 201);
        } catch (\Database_Exception $e) {
            // ユーザー内ユニーク違反など
            return $this->response(['error'=>'conflict','message'=>$e->getMessage()], 409);
        }
    }

    public function put_view($id)
    {
        \Security::check_token();
        $uid = \Session::get('user_id');
        $d = \Input::json();

        // ユーザー制約（user_id を指定させない）
        $q = \DB::update('companies')->set([
            'name'           => $d['name'] ?? \DB::expr('name'),
            'status_id'      => isset($d['status_id']) ? (int)$d['status_id'] : \DB::expr('status_id'),
            'website_url'    => array_key_exists('website_url',$d) ? $d['website_url'] : \DB::expr('website_url'),
            'position_title' => array_key_exists('position_title',$d) ? $d['position_title'] : \DB::expr('position_title'),
            'employment_type'=> array_key_exists('employment_type',$d) ? $d['employment_type'] : \DB::expr('employment_type'),
            'location_text'  => array_key_exists('location_text',$d) ? $d['location_text'] : \DB::expr('location_text'),
            'description'    => array_key_exists('description',$d) ? $d['description'] : \DB::expr('description'),
        ])->where('id',$id)->where('user_id',$uid);
        $q->execute();
        return $this->response(['ok'=>true]);
    }

    public function delete_view($id)
    {
        \Security::check_token();
        $uid = \Session::get('user_id');
        \DB::delete('companies')->where('id',$id)->where('user_id',$uid)->execute();
        return $this->response(['ok'=>true]);
    }
}

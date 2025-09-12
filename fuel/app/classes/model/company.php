<?php
// fuel/app/classes/model/company.php
class Model_Company extends \Model
{
    public static function validate(array $data = [])
    {
        $v = \Validation::forge();
        $v->add('name', '企業名')->add_rule('required')->add_rule('max_length', 100);
        $v->add('status_id', 'ステータス')->add_rule('required')->add_rule('valid_string', ['numeric']);
        $v->add('website_url', 'URL')->add_rule('valid_url')->add_rule('max_length', 255);
        $v->add('position_title','職種')->add_rule('max_length',100);
        $v->add('employment_type','雇用形態')->add_rule('max_length',30);
        $v->add('location_text','勤務地')->add_rule('max_length',100);
        // 部分更新時は required を緩めたい → 呼び出し側でフィールドを絞って渡す
        return $v;
    }

    public static function all_by_user(int $uid): array
    {
        return \DB::select('c.*','s.key','s.label_ja','s.color_hex')
            ->from(['companies','c'])
            ->join(['statuses','s'],'INNER')->on('c.status_id','=','s.id')
            ->where('c.user_id',$uid)
            ->order_by('c.id','asc')
            ->execute()->as_array();
    }

    public static function create(int $uid, array $data): int
    {
        list($id,) = \DB::insert('companies')->set([
            'user_id'        => $uid,
            'status_id'      => (int)$data['status_id'],
            'name'           => $data['name'],
            'website_url'    => $data['website_url']    ?? null,
            'position_title' => $data['position_title'] ?? null,
            'employment_type'=> $data['employment_type']?? null,
            'location_text'  => $data['location_text']  ?? null,
            'description'    => $data['description']    ?? null,
        ])->execute();
        return (int)$id;
    }

    public static function find_one(int $uid, int $id): ?array
    {
        $row = \DB::select()->from('companies')
            ->where('user_id',$uid)->where('id',$id)
            ->execute()->current();
        return $row ?: null;
    }

    public static function update_one(int $uid, int $id, array $data): int
    {
        // 渡ってきたキーだけ更新（部分更新対応）
        $set = [];
        foreach (['status_id','name','website_url','position_title','employment_type','location_text','description'] as $k) {
            if (array_key_exists($k, $data)) {
                $set[$k] = $data[$k];
            }
        }
        if (!$set) return 0;

        return \DB::update('companies')->set($set)
            ->where('user_id',$uid)->where('id',$id)
            ->execute();
    }

    public static function update_status(int $uid, int $id, int $status_id): int
    {
        return \DB::update('companies')->set(['status_id'=>$status_id])
            ->where('user_id',$uid)->where('id',$id)
            ->execute();
    }

    public static function delete_one(int $uid, int $id): int
    {
        return \DB::delete('companies')
            ->where('user_id',$uid)->where('id',$id)
            ->execute();
    }
}

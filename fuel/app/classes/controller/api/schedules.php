<?php
class Controller_Api_Schedules extends \Controller_Rest
{
    protected $format = 'json';

    public function get_index()
    {
        $uid = \Session::get('user_id');
        $rows = \DB::select()->from('schedules')
            ->where('user_id',$uid)
            ->where('start_at','>=', date('Y-m-d 00:00:00'))
            ->order_by('start_at','asc')->execute()->as_array();
        return $this->response($rows);
    }
}

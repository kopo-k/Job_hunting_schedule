<?php
// fuel/app/classes/controller/applications.php

class Controller_Applications extends Controller_Base  // ← ここを変更（\Controller → Controller_Base）
{
    public function action_index()
    {
        $user_id = \Session::get('user_id', 1);

        $statuses = \DB::select()->from('statuses')
            ->order_by('id','asc')->execute()->as_array();

        $companies = \DB::select()->from('companies')
            ->where('user_id', $user_id)
            ->order_by('updated_at','desc')->execute()->as_array();

        $events = \DB::select()->from('schedules')
            ->where('user_id', $user_id)
            ->where('start_at', '>=', date('Y-m-d 00:00:00'))
            ->order_by('start_at','asc')->execute()->as_array();

        $data = compact('statuses','companies','events');

        // このビューは自動出力フィルタを使わない（第3引数 false）
        return \Response::forge(\View::forge('applications/index', $data, false));
    }
}

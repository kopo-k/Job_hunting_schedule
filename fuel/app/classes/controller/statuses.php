<?php

use Fuel\Core\Controller_Rest;
use Fuel\Core\DB;

/**
 * ステータスAPI（標準パターン）
 */
class Controller_Statuses extends Controller_Rest
{
    protected $format = 'json';

    public function get_index()
    {
        $rows = DB::select()->from('statuses')->order_by('id','asc')->execute()->as_array();
        return $this->response($rows);
    }
}
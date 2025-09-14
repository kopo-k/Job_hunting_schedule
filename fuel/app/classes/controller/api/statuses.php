<?php

use Fuel\Core\DB;

// fuel/app/classes/controller/api/statuses.php

class Controller_Api_Statuses extends Controller_Api_Base
{
    // jsで扱いやすくするためにAPIは常にJSONで返す
    protected $format = 'json';

    public function get_index()
    {
        $rows = DB::select()->from('statuses')->order_by('id','asc')->execute()->as_array();
        return $this->response($rows);
    }
}
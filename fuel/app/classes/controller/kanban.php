<?php
class Controller_Kanban extends Controller_Base
{
    public function action_index()
    {
        return \View::forge('kanban/index');
    }
}
<?php

class Controller_Applications extends Controller
{
    public function action_index()
    {
        // 一覧表示用ビューを返す
        return Response::forge(View::forge('applications/index'));
    }
}

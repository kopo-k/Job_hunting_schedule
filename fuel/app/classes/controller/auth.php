<?php

use Fuel\Core\Controller;
use Fuel\Core\View;
use Fuel\Core\Response;

class Controller_Auth extends Controller
{
  public function action_signup()
  {
    return Response::forge(View::forge('auth/signup'));
  }

  public function action_login()
  {
    return Response::forge(View::forge('auth/login'));
  }
}

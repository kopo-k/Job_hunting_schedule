<?php
class Controller_Kanban extends \Controller {
  public function action_index() {
    return \Response::forge(\View::forge('kanban/index'));
  }
}

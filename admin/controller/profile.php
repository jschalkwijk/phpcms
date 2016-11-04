<?php

use CMS\model\Controller\Controller;
use CMS\model\Users\Users;
class Profile extends Controller{
	public function index(){
		$params = [$_SESSION['user_id'],$_SESSION['username']];
		$user = Users::fetchSingle('users',$params[0]);
		$this->view('Add contact',['users/profile.php'],$params,['user' => $user]);
	}
}
?>
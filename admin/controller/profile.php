<?php

class Profile extends Controller{
	public function index(){
		$params = [$_SESSION['user_id'],$_SESSION['username']];
		$user = users_Users::fetchSingle('users',$params[0]);;
		$this->view('Add contact',['profile.php'],$params,['user' => $user]);
	}
}
?>
<?php

use CMS\Models\Controller\Controller;
use CMS\Models\Users\Users;
class Profile extends Controller{
	public function index($response,$params = null){
		$params = [$_SESSION['user_id'],$_SESSION['username']];
		$user = Users::fetchSingle('users',$params['id']);
		$this->view('Add contact',['users/profile.php'],$params,['user' => $user]);
	}
}
?>
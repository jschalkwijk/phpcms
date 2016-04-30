<?php
class Encrypt {
	
	public function password_encrypt($password){
		$hash_format = "$2y$10$"; //blowfish	
		$salt = $this->generate_salt();
		$hash_salt = $hash_format.$salt;
		$hash = crypt($password,$hash_salt);
		return $hash;
	}
	
	private function generate_salt(){
		$unique_random_string = md5(uniqid(mt_rand(),true));
		$base64_string = base64_encode($unique_random_string);
		$modified_base64_string = str_replace('+', '.', $base64_string);
		$salt = substr($modified_base64_string, 0,22);
		return $salt;
	}
}
?>
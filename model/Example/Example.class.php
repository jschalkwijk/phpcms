<?php

class example_Example {
	private $message;
	
	public function __construct($message){
		$this->message = $message;
	}
	
	public static function helloWorld(){
		$example = new example_Example('Hello World');
		return ['example' => $example];
	}
	
	public function displayMessage(){
		return $this->message;
	}
}
?>
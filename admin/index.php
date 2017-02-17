<?php

/*
** Version - 0.5
** PHP Framework/CMS
** Author - Jorn Schalkwijk
** Website - http://www.jornschalkwijk.com
** License: Free to use/modify for non commercial use only.
*/

// package index.php
//ROUTING
require_once('config.php');
use CMS\Models\DBC\DBC;
use CMS\Core\Auth\Auth;

Auth::authenticate();

ob_start();

class APP {
	private $controller = 'Dashboard';
	private $routes = array();
	private $params = array();
	private $method;
	
	public function router(){
		// call URL metod to split the URL and add it to the class $routes.
		$this->url();
		
		//Get the Controller	
		if(isset($this->routes[0])) {
			// $routes[0] resembles the controller file we want to call.
			$route = ucfirst($this->routes[0]);
			// check if the controller file exists and assign to $controller.
			if(file_exists('Controller/'.$route.'Controller.php')){
				$this->controller = $route;
				// delete key/value pair from array
				unset($this->routes[0]);
				// inlcude the controller
				require_once('Controller/'.$this->controller.'Controller.php');
				// call new controller
				$app = new $this->controller;
			} else {
				require_once('Controller/'.$this->controller.'Controller.php');
				$app = new $this->controller;
				// set error message 
				$app->error = 'Page not found';
				// throw error
				$app->err();
				exit();
			}
			// the second part of the url is the method in the controlle we want to call
			// if a method is specified, call the method
			if(isset($this->routes[1])){
				if(method_exists($this->controller,$this->routes[1])) {
					$this->method = $this->routes[1];
					$method = $this->routes[1];
					// delete key/value pair from array
					unset($this->routes[1]);
					// array_values resets the array keys to 0,1,2 etc
					// the leftover from the url are the parameters you can use and pass to a method
					$this->params = array_values($this->routes);
					// her we call the controller->methods(parameters) and pass the parameters to the method;		
					$app->$method($this->params);
				} else {
					$app->error = 'Page not found';
					$app->err();
					exit();
				}
			} else {
			// no method is selected, call the controllers index method
				$app->index();
			}
		// if there is no controller specified, render the main dashboard page.			
		} else {
			require_once('Controller/'.$this->controller.'Controller.php');
			$app = new $this->controller;
			$app->index();
		}			
	}//
	
	private function url(){
		if(isset($_GET['url'])){
			// get the URL from the base defined in the.htaccess file.
			// filter url
			$url = filter_var(trim($_GET['url']),FILTER_SANITIZE_URL);
			// delete last / if it is there.
			$url = rtrim($url,'/');
			// Remove the - (dash) in the url : EX. admin/add-user. Classnames can't have the - (dash) so class is written as AddUser.
			// to call the function we need to remove the - (dash)
			$url = str_replace('-','', $url );
			// create array with all the url parts.
			$url = explode('/',$url);
			// add all array values to the class var routes.
			foreach($url as $key => $value){
				$this->routes[$key] = $value;
			}
		}
	}//
}

// start the application
$app = new APP();
$app->router();

ob_end_flush();
?>
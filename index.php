<?php

/*
 ** Version - 0.5
 ** Author - Jorn Schalkwijk
 ** Website - http://www.jornschalkwijk.nl
 ** License: Free to use/modify for non commercial use only.
*/

# Routing

require_once('config.php');
use \CMS\Models\DBC\DBC;

ob_start();

class APP {
	// Standard controller or homepage/root
	private $controller = 'home';
	private $routes = array();
	private $params = array();
	private $method;

	// This method will deal with the controllers needed specified in the URL.
	// The URL gets parsed by the url method
	public function router(){
		// call URL metod to split the URL and add it to the class $this->routes.
		$this->url();
		$routes = $this->routes;
		//Get the Controller
		if(isset($routes[0])) {
			/*
			 * $routes[0] resembles the controller file we want to call from the controller folder.
			 * each controller has a index() func which is called if no other parameters are specified in the URL.
			*/
			$route = $routes[0];
			// check if the controller file exists and assign to $controller.
			if(file_exists('controller/'.$route.'.php')){
				$this->controller = $route;
				// delete key/value pair from array
				unset($routes[0]);
				// inlcude the controller
				require_once('controller/'.$this->controller.'.php');
				// call new controller
				$app = new $this->controller;
			} else {
				require_once('controller/'.$this->controller.'.php');
				$app = new $this->controller;
				// set error message
				$app->error = 'Page not found';
				// throw error
				$app->err();
				exit();
			}
			/*
			 * The second part of the url is the method in the controlle we want to call,
			 * if a method is specified, call the method.
			*/
			if(isset($routes[1])){
				if(method_exists($this->controller,$routes[1])) {
					$this->method = $routes[1];
					$method = $routes[1];
					// delete key/value pair from array
					unset($routes[1]);
					// array_values resets the array keys to 0,1,2 etc
					// the leftover from the url are the parameters you can use and pass to a method like you used to do with GET variables
					$this->params = array_values($routes);
					// here we call the controller->methods(parameters) and pass the parameters to the method;
					$app->$method($this->params);
				} else {
					$app->error = 'Page not found';
					/*
	 				 * if a controller that doesn't exist is being called by the index.php App class, the
	 				 * err method is called to display the custom 404 page.
	 				 *
	 				*/
					$app->err();
					exit();
				}
			} else {
				// if no method is selected, call the controllers index method
				// $this->route contains the controller name and possible method name to id the current page for meta tags
				$app->index($this->routes);
			}
			// if there is no controller specified, render the main dashboard page.
		} else {
			require_once('controller/'.$this->controller.'.php');
			$app = new $this->controller;
			$app->index(['Home']);
		}
	}
	/*
	 * Parses the url which will be explode at the / and creates an array which can be uses in the router.
	*/
	private function url(){

		if(isset($_GET['url'])){
			// get the URL from the base defined in the.htaccess file.
			// filter url
			# Example: www.yourdomain.com/example-page/hello/1/title/

			$url = filter_var(trim($_GET['url']),FILTER_SANITIZE_URL);
			// delete last / if it is there.
			$url = rtrim($url,'/');
			# Exampele change 1: $url = example-page/hello/1/title

			/*
			 * Remove the - (dash) in the url : EX. example-page/hello. Classnames can't have the - (dash) so class is written as examplePage.
			 * to call the function we need to remove the - (dash)
			*/

			# Example change 2: $url = examplepage/hello/1/title
			$url = str_replace('-','', $url );
			// create array with all the url parts.
			# Example change 3: $url = ["examplepage","hello",1,"title"]
			$url = explode('/',$url);

			// add all array values to the class var routes.
			foreach($url as $key => $value){
				$this->routes[$key] = $value;
			}
		}
	}
}

# Start Application
$app = new APP();
$app->router();

ob_end_flush();
?>
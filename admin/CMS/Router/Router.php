<?php
    /**
     * Created by PhpStorm.
     * User: jorn
     * Date: 22-04-17
     * Time: 14:43
     */

    namespace CMS\Router;
    use CMS\Router\Exceptions\RouteNotFoundException;
    use CMS\Router\Exceptions\MethodNotAllowedException;

    /**
     * Class Router
     * @package CMS\Router
     */
    class Router
    {
        public $path;
        public $routes = [];
        public $methods = [];
        public $params;
        public $bindings = [];
        public $test;
        public $uri;
        public $group;
        public $prefix;
        public $match;

        /**
         * Define the named parameters and preg match patterns in this array
         * Will be used by parseURL()
         * @var array
         */
        protected $pattern = [
            ':id' => '\d',
            ':name' => '[a-zA-Z0-9-_.]',
            ':num' => '[0-9]',
            ':alpha' => '[a-zA-Z]',
            ':alphaNum' => '[a-zA-Z0-9]',
        ];

        /**
         * @return mixed
         */
        public function getParams()
        {
            return $this->params;
        }
        /**
         * Setting the current path
         * @param $path
         */
        public function setPath($path)
        {
            $this->path = $path;
            // Will split the current url so we can evaluate possible parameters.
            $this->params();

        }

        /**
         * Adding a new route
         * @param $uri
         * @param $handler
         * @param array $methods
         */
        public function addRoute($uri, $handler, array $methods = [])
        {
            // Prefix is possibly set in ./routes/routes.php with the group closure.
            if(!empty($this->prefix)){
                $uri = $this->prefix.$uri;
            }
            // Set the route uri as key and the aray woth the class and method as value
            $this->routes[$uri] = $handler;
            // Set the methods array with uri as key and the array with the request method('s)
            $this->methods[$uri] = $methods;
            // Parse the user defined route for bindings defined in the patters variable.
            $this->bindings[$uri] = $this->parseUrl($uri);
        }

        /**
         * @param $prefix
         */
        public function group($prefix)
        {
            if(empty($this->group)) {
                $this->group[$prefix] = $prefix;
            } else {
                reset($this->group);
                $first = key($this->group);
                $this->group[$prefix] = $first.$prefix;
            }
            $this->prefix = $this->group[$prefix];
        }

        /**
         * @return mixed
         * @throws MethodNotAllowedException
         * @throws RouteNotFoundException
         */
        public function getResponse()
        {
            if($this->path == '/'){
                return $this->routes[$this->path];
            }
            foreach ($this->bindings as $key => $value) {
                $string = "/" . ltrim(implode("\/+", $value), "\/+") . "+$/";
                $this->match = $string;
                if (preg_match($string, $this->path)) {

                    $this->params = array_combine(
                        str_replace(
                            ':','',
                            array_flip(
                                $this->bindings[$key]
                            )
                        ),
                        $this->params
                    );

                    if (!in_array($_SERVER['REQUEST_METHOD'], $this->methods[$key])) {
                        throw new MethodNotAllowedException('Method not Allowed!');
                    }
                    return $this->routes[$key];
                }
            }

            throw new RouteNotFoundException('No route found!');
        }

        /**
         * Sets the $params array by exploding at the '/'.
         */
        private function params(){
            if(isset($this->path)){
                // get the URL from the base defined in the.htaccess file.
                // filter url
                $url = filter_var(trim($this->path),FILTER_SANITIZE_URL);
                // delete last / if it is there.
                $url = rtrim($url,'/');
                // create array with all the url parts.
                $url = explode('/',$url);
                // add all array values to the $params array.
                foreach($url as $key => $value){
                    $this->params[$key] = $value;
                }
            }
        }

        /**
         * Takes the user defined uri and sets named parameters
         * to there preg_match pattern value from the $patterns array.
         * @param $uri
         * @return array
         */
        private function parseUrl($uri){
            $bindings = [];

            if(isset($uri)){
                // filter url
                $url = filter_var(trim($uri),FILTER_SANITIZE_URL);
                // delete last / if it is there.
                $url = rtrim($url,'/');
                // create array with all the url parts.
                $url = explode('/',$url);
                // add all array values to the $bindings array.
                foreach($url as $key => $value){
                    if(!empty($key) && !empty($value)) {
                        $bindings[$value] = $value;
                        //if a binding is set in the patterns array, set it's value to the pattern.
                        if (isset($this->pattern[$value])) {
                            $bindings[$value] = $this->pattern[$value];
                        }
                    }
                }
            }
            return $bindings;
        }
    }
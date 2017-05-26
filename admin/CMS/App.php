<?php

    namespace CMS;
    use CMS\Router\Response;
    use CMS\Router\Exceptions\RouteNotFoundException;
    use CMS\Router\Exceptions\MethodNotAllowedException;

    class App
    {
        public $container;

        public function __construct($container)
        {
            $this->container = $container;
        }

        public function getContainer()
        {
            return $this->container;
        }

        public function get($uri, $handler)
        {
            $this->container->router->addRoute($uri, $handler, ['GET']);
        }

        public function post($uri, $handler)
        {
            $this->container->router->addRoute($uri, $handler, ['POST']);
        }

        public function map($uri, $handler, array $methods = ['GET'])
        {
            $this->container->router->addRoute($uri, $handler, $methods);
        }

        public function group($prefix,\Closure $callback)
        {
//            $router =  $this->container->router;
//            if(empty($router->group)) {
//                $router->group[$prefix] = $prefix;
//            } else {
//                reset($router->group);
//                $first = key($router->group);
//                $router->group[$prefix] = $first.$prefix;
//            }
//            $router->prefix = $router->group[$prefix];
//            call_user_func($callback, $this,$this->container);
            $this->container->router->group($prefix);
            call_user_func($callback, $this,$this->container);
        }

        public function run()
        {
            $router = $this->container->router;
            $router->setPath($_GET['url'] ?? '/');

            try {
                $response = $router->getResponse();
            } catch (RouteNotFoundException $e) {
                if ($this->container->has('errorHandler')) {
                    $response = $this->container->errorHandler;
                } else {
                    return;
                }
            } catch (MethodNotAllowedException $e) {
                if ($this->container->has('errorHandler')) {
                    $response = $this->container->errorHandler;
                } else {
                    return;
                }
            }
            return $this->respond($this->process($response));
//            echo "Response: ";
//            print_r($response);
//            echo "<br><br>PregMatch: <br>";
//            print_r($router->match);
//            echo "<br><br>Routes: <br>";
//            print_r($router->routes);
//            echo "<br><br>Route Groups: <br>";
//            print_r($router->group);
//            echo "<br><br>Methods: <br>";
//            print_r($router->methods);
//            echo "<br><br>Params: <br>";
//            print_r($router->params);
//            echo "<br><br>Bindings: <br>";
//            print_r($router->bindings);
//            print_r($router->match);
//            echo "<br><br>Container: <br>";
//            print_r($this->container);
        }

        protected function process($callable)
        {
            $response = $this->container->response;

            if (is_array($callable)) {
                if (!is_object($callable[0])) {
                    $callable[0] = new $callable[0];
                }

                return call_user_func($callable, $response,$this->container->router->getParams());
            }

            return $callable($response,$this->container->router->getParams());
        }

        protected function respond($response)
        {
            if (!$response instanceof Response) {
                echo $response;
                return;
            }

            header(sprintf(
                'HTTP/%s %s %s',
                '1.1',
                $response->getStatusCode(),
                ''
            ));

            foreach ($response->getHeaders() as $header) {
                header($header[0] . ': ' . $header[1]);
            }

            echo $response->getBody();
        }
    }

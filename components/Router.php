<?php

    class Router
    {
        private $routes;

        function __construct()
        {
            $routespath = ROOT.'/config/routes.php';
            $this->routes = include $routespath;
        }

        private function getURI()
        {
            if (!empty($_SERVER['REQUEST_URI']))
            {
                return trim($_SERVER['REQUEST_URI'], '/');
            }

            return null;
        }

        public function run()
        {
            $uri = $this->getURI();
            if (empty($uri))
            {
                $controllerName = 'IndexController';
                $actionName = 'actionIndex';

                $controllerFile = ROOT . '/controllers/' .$controllerName. '.php';
                if (file_exists($controllerFile))
                {
                    include_once($controllerFile);
                }

                $controllerObject = new $controllerName;
                $result = $controllerObject->$actionName();
                exit;

            }

            foreach ($this->routes as $path)
            {
                if(preg_match("~$path~", $uri))
                {

                    $segments = explode('/', $path);

                    $controllerName = array_shift($segments).'Controller';
                    $controllerName = ucfirst($controllerName);

                    $actionName = 'action'.ucfirst((array_shift($segments)));

                    $controllerFile = ROOT . '/controllers/' .$controllerName. '.php';
                    if (file_exists($controllerFile))
                    {
                        include_once($controllerFile);
                    }

                    $controllerObject = new $controllerName;
                    $result = $controllerObject->$actionName();
                    if ($result != null)
                    {
                        break;
                    }
                }
                elseif (!preg_match("~/~", $uri))
                {
                    $controllerName = ucfirst($uri).'Controller';
                    $controllerFile = ROOT . '/controllers/' .$controllerName. '.php';
                    if (file_exists($controllerFile))
                    {
                        include_once($controllerFile);
                    }

                    $actionName = 'actionIndex';

                    $controllerObject = new $controllerName;
                    $result = $controllerObject->$actionName();

                    if ($result == null)
                    {
                        break;
                    }
                }
            }
        }
    }
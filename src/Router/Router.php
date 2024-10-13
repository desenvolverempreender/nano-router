<?php

namespace Nano\Router;

class Router
{
    public $routes = array();
    
    private $path;
    private $base_path;
    private $expr;
    private $matches;
    private $callback;
    private $methods = array('GET', 'POST', 'HEAD', 'PUT', 'DELETE');

    public function __construct($base_path = '')
    {
        $this->base_path = $base_path;
        $path = urldecode(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH));
        $path = substr($path, strlen($base_path));
        $this->path = $path;
    }

    private function setRoute($expr, $callback, $methods = null)
    {
        $this->expr = '#^' . $expr . '/?$#';
        $this->callback = $callback;

        if ($methods !== null) {
            $this->methods = is_array($methods) ? $methods : array($methods);
        }
    }

    private function matches($path)
    {
        if (preg_match($this->expr, $path, $this->matches) &&
            in_array($_SERVER['REQUEST_METHOD'], $this->methods)) {
            return true;
        }

        return false;
    }

    private function exec()
    {
        return call_user_func_array($this->callback, array_slice($this->matches, 1));
    }

    public function all($expr, $callback, $methods = null)
    {
        $this->setRoute($expr, $callback, $methods);
        $this->routes[] = clone $this; // Store a copy of the Router with the set route
    }

    public function add($expr, $callback, $methods = null)
    {
        $this->all($expr, $callback, $methods);
    }

    public function get($expr, $callback)
    {
        $this->all($expr, $callback, 'GET');
    }

    public function post($expr, $callback)
    {
        $this->all($expr, $callback, 'POST');
    }

    public function head($expr, $callback)
    {
        $this->all($expr, $callback, 'HEAD');
    }

    public function put($expr, $callback)
    {
        $this->all($expr, $callback, 'PUT');
    }

    public function delete($expr, $callback)
    {
        $this->all($expr, $callback, 'DELETE');
    }

    public function dispatch()
    {
        foreach ($this->routes as $route) {
            if ($route->matches($this->path)) {
                return $route->exec();
            }
        }
        throw new \Exception('No routes matching' . $this->path);
    }

    public function url($path = null)
    {
        if ($path === null) {
            $path = $this->path;
        }

        return $this->base_path . $path;
    }

    public function redirect($from_path, $to_path, $code = 302)
    {
        $this->all($from_path, function () use ($to_path, $code) {
            http_response_code($code);
            header("Location: {$to_path}");
        });
    }
}

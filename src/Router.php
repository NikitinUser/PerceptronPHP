<?php

namespace NikitinUser\perceptronPHP;

use NikitinUser\perceptronPHP\app\Controllers\PerceptronController;

class Router
{
    public const CONTROLLERS_NAMESPACE = "NikitinUser\\perceptronPHP\\app\\Controllers\\";

    /**
     * must be symbols / on start and on end
     * example /getSmth/
     */
    public const ROUTES = [
        "/perceptron/getOutputs/" => "PerceptronController:getOutputByPerceptron"
    ];

    public function main(string $route)
    {
        $routeExec = $this->getRoute($route);

        if (empty($routeExec)) {
            throw new \Exception("ERROR: route " . $routeExec . " not found");
        }

        $routeClass = $this->getClassFromRoute($routeExec);
        $routeMethod = $this->getMethodFromRoute($routeExec);

        $classFull = self::CONTROLLERS_NAMESPACE . $routeClass;

        if (!class_exists($classFull)) {
            throw new \Exception("ERROR: class " . $classFull . " does not exist");
        }
        
        if (!method_exists($classFull, $routeMethod)) {
            throw new \Exception("ERROR: method " . $routeMethod . " in class " . $classFull . " does not exist");
        }

        $obj = new $classFull();
        return $obj->$routeMethod();
    }

    private function getRoute(string $route): string
    {
        return self::ROUTES[$route] ?? "";
    }

    private function getClassFromRoute(string $route): string
    {
        $route = explode(":", $route);
        return $route[0];
    }

    private function getMethodFromRoute(string $route): string
    {
        $route = explode(":", $route);
        return $route[1];
    }
}

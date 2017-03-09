<?php

namespace Sway\Component\Controller\Exception;

class ControllerException extends \Exception
{
    /**
     * Throws an exception when controller's path is invalid
     * @param string $controllerPath
     * @return \Sway\Component\Controller\Exception\ControllerException
     */
    public static function invalidControllerPath(string $controllerPath) : ControllerException
    {
        return (new ControllerException(sprintf("Controller's path '%s' is invalid", $controllerPath)));
    }
    
    /**
     * Throws an exception when controller's method is not public
     * @param string $controllerPath
     * @return \Sway\Component\Controller\Exception\ControllerException
     */
    public static function controllerMethodNotPublic(string $controllerPath) : ControllerException
    {
        return (new ControllerException(sprintf("Method '%s' must be public", $controllerPath)));
    }
    
    /**
     * Throws an exception when controller's method is static
     * @param string $controllerPath
     * @return \Sway\Component\Controller\Exception\ControllerException
     */
    public static function controllerMethodIsStatic(string $controllerPath) : ControllerException
    {
        return (new ControllerException(sprintf("Method '%s' cannot be static", $controllerPath)));
    }
    
    /**
     * Throws an exception when service container was not found
     * @param string $dependencyName
     * @return \Sway\Component\Controller\Exception\ControllerException
     */
    public static function serviceContainerNotFound(string $dependencyName) : ControllerException
    {
        return (new ControllerException(sprintf("Service container not found as dependency '%s'", $dependencyName)));
    }
}


?>

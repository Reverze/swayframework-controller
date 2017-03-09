<?php

namespace Sway\Component\Controller;

use Sway\Component\Controller\Exception;

use Sway\Component\Dependency\DependencyInterface;
use Sway\Component\Regex\Regex;
use Sway\Component\Http\Request;


class Invoker extends DependencyInterface
{
    /**
     * Regular expression for controller's path
     * @var string
     */
    private $controllerPathRegexExp = null;
    
    public function __construct() 
    {
        $this->controllerPathRegexExp = "^(\\\\{0,1}[a-zA-Z0-9]+)+:[a-zA-Z0-9]+$";
    }
    
    /**
     * Creates a new controllers invoker
     * @return \Sway\Component\Controller\Invoker
     */
    public static function create()
    {
        $invoker = new Invoker();
        return $invoker;
    }
    
    /**
     * Checks if controller path is valid
     * @param string $controllerPath
     * @return bool
     */
    public function isValidControllerPath(string $controllerPath) : bool
    {      
        $regex = new Regex($controllerPath);
        $valid = $regex->isMatch($this->controllerPathRegexExp);
        return $valid;
        
    }
    
    /**
     * 
     * @param string $controllerPath
     * @return array
     */
    public function getCallableControllerPath(string $controllerPath) : array
    {
        return explode(":",$controllerPath);
    }
    
    public function executeController(string $controllerPath, $controllerParameters)
    {
        /**
         * Checks if controller's path is valid
         */
        if (!$this->isValidControllerPath($controllerPath)){
            throw Exception\ControllerException::invalidControllerPath($controllerPath);
        }
        
        /**
         * Gets callable version of controller's path
         */
        $callableControllerPath = $this->getCallableControllerPath($controllerPath);
        
        $methodReflector = new \ReflectionMethod($callableControllerPath[0], $callableControllerPath[1]);
        
        /**
         * Controller's method must be public
         */
        if (!$methodReflector->isPublic()){
            throw Exception\ControllerException::controllerMethodNotPublic($controllerPath);
        }
        
        /**
         * Controller's method cannot be static
         */
        if ($methodReflector->isStatic()){
            throw Exception\ControllerException::controllerMethodIsStatic($controllerPath);
        }
        
        /**
         * Array which contains method's parameters
         */
        $methodParameters = $methodReflector->getParameters();
        
        /**
         * 
         */
        $argumentsToPass = array();
        
        foreach($methodParameters as $methodParameter){
            $parameterClass = $methodParameter->getClass();
            
            $methodParameterName = $methodParameter->getName();
            
            if (isset($controllerParameters[$methodParameterName])){
                array_push($argumentsToPass, $controllerParameters[$methodParameterName]);
            }
            
            if (!empty($parameterClass)){
                array_push($argumentsToPass, $this->createInvokeDependency($parameterClass->getName()));
            }
        }
        
        $callableControllerPath[0] = new $callableControllerPath[0];
        
        $this->getDependency('injector')->inject($callableControllerPath[0]);
        
        return call_user_func_array($callableControllerPath, $argumentsToPass);
    }
    
    protected function createInvokeDependency(string $ivokeDependencyName)
    {
         if ($ivokeDependencyName === 'Sway\Component\Http\Request'){
             return (new Request());
         }
        
    }
    
}


?>


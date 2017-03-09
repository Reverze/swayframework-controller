<?php

namespace Sway\Component\Controller;

use Sway\Component\Dependency\DependencyInterface;
use Sway\Component\Http\Response;
use Sway\Component\Route\Manager;
use Sway\Component\Service\Container;

class Controller extends DependencyInterface
{
    /**
     * Service container
     * @var \Sway\Component\Service\Container
     */
    private $serviceContainer = null;
    
    public function __construct()
    {
       
    }
    
    protected function dependencyController() 
    {
        
    }
    
    /**
     * Gets service by service's name
     * @param string $serviceName
     * @return object
     * @throws \Sway\Component\Controller\Exception\ControllerException
     */
    public function get(string $serviceName)
    {
        /**
         * If reference to service container is not stored yet
         */
        if (empty($this->serviceContainer)){
            /**
             * Gets reference to dependency
             */
            $this->serviceContainer = $this->getDependency('serviceContainer');
            
            /**
             * If service container is not exists
             */
            if (empty($this->serviceContainer)){
                throw Exception\ControllerException::serviceContainerNotFound('serviceContainer');
            }
        }
        
        return $this->serviceContainer->get($serviceName);
    }
    
    
    protected function redirectToRoute(string $routeName, array $routeParameters = array())
    {
        $routePathUri = $this->getDependency('router')->createUri($routeName, $routeParameters);
        
        /**
         * TO CHANGE! Use httpd interface
         */
        $routePathUri = $_SERVER['SCRIPT_NAME'] . $routePathUri;
        
       
        $response = new Response();
        $response->redirectTo($routePathUri);
        return $response;
    }
    
    /**
     * Do some stuff before service request
     */
    protected function doSomeStuff()
    {
        
    }
    
}


?>

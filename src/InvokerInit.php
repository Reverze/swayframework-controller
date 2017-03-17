<?php

namespace Sway\Component\Controller;

use Sway\Component\Dependency\DependencyInterface;
use Sway\Component\Init\Component;

class InvokerInit extends Component
{
    /**
     * Initializes controller invoker
     * @return Sway\Component\Controller\Invoker
     */
    public function init()
    {
        $controllerInvoker = Invoker::create();
        return $controllerInvoker;
    }
    
}

?>


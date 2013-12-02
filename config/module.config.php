<?php
use Zend\Mvc\MvcEvent;

return array(
    'EdpSuperluminal' => array(
        // strip all whitespaces and comments? http://www.php.net/manual/en/function.php-strip-whitespace.php
        'strip_whitespace' => true,
        
        'cacheEvent' => array(
            'class' => 'Zend\Mvc\Application',
            'event' => MvcEvent::EVENT_FINISH,
            'priority' => - 100
        )
    )
    
);

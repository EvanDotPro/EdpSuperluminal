<?php
use Zend\Mvc\MvcEvent;

return array(
    'EdpSuperluminal' => array(
        'cacheEvent' => array(
            'class' => 'Zend\Mvc\Application',
            'event' => MvcEvent::EVENT_FINISH,
            'priority' => - 100
        )
    )
);

<?php

namespace Muchacuba\ModelBundle\EventListener;

use Symfony\Component\HttpKernel\Event\FilterResponseEvent;
use JMS\DiExtraBundle\Annotation as Di;

/**
 * @author Yosmany Garcia <yosmanyga@gmail.com>
 *
 * @Di\Service()
 */
class BasicAuthenticationResponseListener
{
    /**
     * @Di\Observe("kernel.response", priority=100)
     */
    public function onKernelResponse(FilterResponseEvent $event)
    {
        if ($event->getResponse()->getStatusCode() == 401) {
            // Removes header to avoid some browsers like chrome to show the
            // authenticate window.
            $event->getResponse()->headers->remove('WWW-Authenticate');
        }
    }
}

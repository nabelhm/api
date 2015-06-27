<?php

namespace Muchacuba\ModelBundle\EventListener;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Event\GetResponseForExceptionEvent;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use JMS\DiExtraBundle\Annotation as Di;

/**
 * @author Yosmany Garcia <yosmanyga@gmail.com>
 *
 * @Di\Service()
 */
class ExceptionListener
{
    /**
     * @Di\Observe("kernel.exception", priority=100)
     */
    public function onKernelException(GetResponseForExceptionEvent $event)
    {
        $exception = $event->getException();

        //if ($exception instanceof AccessDeniedHttpException) {
        if ($exception->getCode() == 403) {
            $code = 403;
        } else {
            $code = 400;
        }

        $response = new JsonResponse(
            array(
                'class' => get_class($exception),
                'message' => $exception->getMessage(),
                'code' => $exception->getCode(),
                'file' => $exception->getFile(),
                'line' => $exception->getLine()
            ),
            $code
        );

        $event->setResponse($response);
    }
}

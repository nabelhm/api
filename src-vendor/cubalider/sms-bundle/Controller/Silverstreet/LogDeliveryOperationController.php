<?php

namespace Cubalider\SmsBundle\Controller\Silverstreet;

use JMS\DiExtraBundle\Annotation as DI;
use Cubalider\Sms\LogDeliveryOperationApiWorker;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * @author Yosmany Garcia <yosmanyga@gmail.com>
 */
class LogDeliveryOperationController
{
    /**
     * @var LogDeliveryOperationApiWorker
     */
    private $logDeliveryOperationApiWorker;

    /**
     * @param LogDeliveryOperationApiWorker $logDeliveryOperationApiWorker
     *
     * @DI\InjectParams({
     *     "logDeliveryOperationApiWorker" = @DI\Inject("cubalider.sms.log_delivery_operation_api_worker"),
     * })
     */
    function __construct(
        LogDeliveryOperationApiWorker $logDeliveryOperationApiWorker
    )
    {
        $this->logDeliveryOperationApiWorker = $logDeliveryOperationApiWorker;
    }

    /**
     * @param Request $request
     *
     * @return Response
     */
    public function logAction(Request $request)
    {
        $this->logDeliveryOperationApiWorker->log(
            $request->get('REFERENCE'),
            $request->get('STATUS'),
            $request->get('TIMESTAMP')
        );

        return new Response();
    }
}

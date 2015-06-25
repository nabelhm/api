<?php

namespace Cubalider\Sms;

use Cubalider\Sms\Message;
use Cubalider\Sms\DeliveryOperation\ConnectToStorageInternalWorker;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use JMS\DiExtraBundle\Annotation as Di;

/**
 * @author Yosmany Garcia <yosmanyga@gmail.com>
 *
 * @Di\Service()
 */
class ProcessDeliveryOperationsApiWorker
{
    /**
     * @var ConnectToStorageInternalWorker
     */
    private $connectToStorageInternalWorker;

    /**
     * @var EventDispatcherInterface
     */
    private $eventDispatcher;

    /**
     * @param ConnectToStorageInternalWorker $connectToStorageInternalWorker
     * @param EventDispatcherInterface       $eventDispatcher
     *
     * @Di\InjectParams({
     *     "connectToStorageInternalWorker" = @Di\Inject("cubalider.sms.delivery_operation.connect_to_storage_internal_worker"),
     *     "eventDispatcher"                = @Di\Inject("event_dispatcher")
     * })
     */
    public function __construct(
        ConnectToStorageInternalWorker $connectToStorageInternalWorker,
        EventDispatcherInterface $eventDispatcher
    ) {
        $this->connectToStorageInternalWorker = $connectToStorageInternalWorker;
        $this->eventDispatcher = $eventDispatcher;
    }

    /**
     * Processes delivery operations.
     *
     * @throws UnknownStatusInternalException
     */
    public function process()
    {
        $items = $this->connectToStorageInternalWorker->connect()
            ->find()
            ->sort([
                'created' => 1
            ]);

        foreach ($items as $item) {
            if ($item['status'] == 'Delivered') {
                $name = 'cubalider.sms.delivered';
            } elseif ($item['status'] == 'Not Delivered') {
                $name = 'cubalider.sms.not_delivered';
            } elseif ($item['status'] == 'Buffered') {
                $name = 'cubalider.sms.buffered';
            } else {
                throw new UnknownStatusInternalException($item['status']);
            }

            $this->eventDispatcher->dispatch(
                $name,
                new DeliveryEvent($item['message'])
            );

            $this->connectToStorageInternalWorker->connect()->remove([
                'message' => $item['message'],
            ]);
        }
    }
}

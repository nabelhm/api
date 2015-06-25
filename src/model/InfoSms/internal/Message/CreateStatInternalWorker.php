<?php

namespace Muchacuba\InfoSms\Message;

use Muchacuba\InfoSms\Message\Stat\ConnectToStorageInternalWorker;
use JMS\DiExtraBundle\Annotation as Di;

/**
 * @author Nabel Hernandez <nabelhm@cubalider.com>
 * @author Yosmany Garcia <yosmanyga@gmail.com>
 *
 * @Di\Service()
 */
class CreateStatInternalWorker
{
    /**
     * @var ConnectToStorageInternalWorker
     */
    private $connectToStorageInternalWorker;

    /**
     * @param ConnectToStorageInternalWorker $connectToStorageInternalWorker
     *
     * @Di\InjectParams({
     *     "connectToStorageInternalWorker" = @Di\Inject("muchacuba.info_sms.message.stat.connect_to_storage_internal_worker")
     * })
     */
    public function __construct(
        ConnectToStorageInternalWorker $connectToStorageInternalWorker
    ) {
        $this->connectToStorageInternalWorker = $connectToStorageInternalWorker;
    }

    /**
     * Creates the stat for given info.
     *
     * @param array  $info
     * @param string $total
     */
    public function create($info, $total)
    {
        $this->connectToStorageInternalWorker->connect()->insert([
            'id' => $info['id'],
            'body' => $info['body'],
            'topics' => $info['topics'],
            'year' => date('Y', $info['created']),
            'month' => date('m', $info['created']),
            'day' => date('d', $info['created']),
            'time' =>  date('H:i:s', $info['created']),
            'total' => $total,
            'delivered' => 0,
            'notDelivered' => 0
        ]);
    }
}

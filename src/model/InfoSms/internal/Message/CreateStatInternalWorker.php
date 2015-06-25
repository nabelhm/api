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
        $today = time();
        $year = date('Y', $today);
        $month = date('m', $today);
        $day = date('d', $today);

        $this->connectToStorageInternalWorker->connect()->insert([
            'id' => $info['id'],
            'year' => $year,
            'month' => $month,
            'day' => $day,
            'body' => $info['body'],
            'topics' => $info['topics'],
            'total' => $total,
            'delivered' => 0,
            'notDelivered' => 0
        ]);
    }
}

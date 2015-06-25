<?php

namespace Muchacuba\InfoSms\Message;

use Muchacuba\InfoSms\Message\Stat\ConnectToStorageInternalWorker;
use Muchacuba\InfoSms\Message\Stat\NonExistentIdInternalException;
use JMS\DiExtraBundle\Annotation as Di;

/**
 * @author Nabel Hernandez <nabelhm@cubalider.com>
 * @author Yosmany Garcia <yosmanyga@gmail.com>
 *
 * @Di\Service()
 */
class IncreaseNotDeliveredStatInternalWorker
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
     * Increases the not delivered stat.
     *
     * @param string $info
     *
     * @throws NonExistentIdInternalException
     */
    public function increase($info)
    {
        $result = $this->connectToStorageInternalWorker->connect()->update(
            array('id' => $info),
            array('$inc' => array(
                'notDelivered' => 1
            ))
        );

        if ($result['n'] == 0) {
            throw new NonExistentIdInternalException();
        }
    }
}

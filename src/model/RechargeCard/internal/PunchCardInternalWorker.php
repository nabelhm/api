<?php

namespace Muchacuba\RechargeCard;

use Muchacuba\RechargeCard\Card\ConnectToStorageInternalWorker;
use Muchacuba\RechargeCard\Card\NonExistentCodeInternalException;
use JMS\DiExtraBundle\Annotation as Di;

/**
 * @author Nabel Hernandez <nabelhm@cubalider.com>
 * @author Yosmany Garcia <yosmanyga@gmail.com>
 *
 * @Di\Service()
 */
class PunchCardInternalWorker
{
    /**
     * @var ConnectToStorageInternalWorker
     */
    private $connectToStorageInternalWorker;

    /**
     * @param ConnectToStorageInternalWorker $connectToStorageInternalWorker
     *
     * @Di\InjectParams({
     *     "connectToStorageInternalWorker" = @Di\Inject("muchacuba.recharge_card.card.connect_to_storage_internal_worker")
     * })
     */
    function __construct(ConnectToStorageInternalWorker $connectToStorageInternalWorker)
    {
        $this->connectToStorageInternalWorker = $connectToStorageInternalWorker;
    }

    /**
     * Marks the card as consumed.
     *
     * @param string $code
     *
     * @throws NonExistentCodeInternalException
     */
    public function punch($code)
    {
        $result = $this->connectToStorageInternalWorker->connect()->update(
            array('code' => $code),
            array('$set' => array(
                'consumed' => true
            ))
        );

        if ($result['n'] == 0) {
            throw new NonExistentCodeInternalException($code);
        }
    }
}
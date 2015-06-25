<?php

namespace Muchacuba\InfoSms;

use Muchacuba\InfoSms\ResellPackage\ConnectToStorageInternalWorker;
use JMS\DiExtraBundle\Annotation as Di;

/**
 * @author Nabel Hernandez <nabelhm@cubalider.com>
 * @author Yosmany Garcia <yosmanyga@gmail.com>
 *
 * @Di\Service()
 */
class CollectResellPackagesApiWorker
{
    /**
     * @var ConnectToStorageInternalWorker
     */
    private $connectToStorageInternalWorker;

    /**
     * @param ConnectToStorageInternalWorker $connectToStorageInternalWorker
     *
     * @Di\InjectParams({
     *     "connectToStorageInternalWorker" = @Di\Inject("muchacuba.info_sms.resell_package.connect_to_storage_internal_worker"),
     * })
     */
    public function __construct(ConnectToStorageInternalWorker $connectToStorageInternalWorker)
    {
        $this->connectToStorageInternalWorker = $connectToStorageInternalWorker;
    }

    /**
     * Collect resell packages, sorted ascending by amount.
     *
     * @return \Iterator An array of resell packages with the following keys:
     *                   id, amount, price and description
     */
    public function collect()
    {
        return $this->connectToStorageInternalWorker->connect()
            ->find()
            ->fields([
                '_id' => 0 // Exclude
            ])
            ->sort([
                'amount' => 1
            ]);
    }
}

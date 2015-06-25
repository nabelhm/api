<?php

namespace Muchacuba\InfoSms;

use JMS\DiExtraBundle\Annotation as Di;
use Muchacuba\InfoSms\ResellPackage\ConnectToStorageInternalWorker;

/**
 * @author Nabel Hernandez <nabelhm@cubalider.com>
 * @author Yosmany Garcia <yosmanyga@gmail.com>
 *
 * @Di\Service()
 */
class CollectResellPackagesTestWorker
{
    /**
     * @var ConnectToStorageInternalWorker
     */
    private $connectToStorageInternalWorker;

    /**
     * @param ConnectToStorageInternalWorker $connectToStorageInternalWorker
     *
     * @Di\InjectParams({
     *     "connectToStorageInternalWorker" = @Di\Inject("muchacuba.info_sms.resell_package.connect_to_storage_internal_worker")
     * })
     */
    public function __construct(
        ConnectToStorageInternalWorker $connectToStorageInternalWorker
    )
    {
        $this->connectToStorageInternalWorker = $connectToStorageInternalWorker;

    }

    /**
     * Collects profiles.
     *
     * @return \Iterator
     */
    public function collect()
    {
        return $this->connectToStorageInternalWorker->connect()
            ->find()
            ->fields([
                '_id' => 0 // Exclude
            ]);
    }
}

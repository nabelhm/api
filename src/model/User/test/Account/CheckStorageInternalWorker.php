<?php

namespace Muchacuba\InfoSms\User\Account;

use JMS\DiExtraBundle\Annotation as Di;
use Cubalider\Unique\Uniqueness\ConnectToStorageInternalWorker as ConnectToUniquenessStorageInternalWorker;
use Muchacuba\Mobile\Profile\ConnectToStorageInternalWorker as ConnectToMobileProfileStorageInternalWorker;
use Muchacuba\Internet\Profile\ConnectToStorageInternalWorker as ConnectToInternetProfileStorageInternalWorker;

/**
 * @author Nabel Hernandez <nabelhm@cubalider.com>
 * @author Yosmany Garcia <yosmanyga@gmail.com>
 *
 * @Di\Service()
 */
class CheckStorageInternalWorker
{
    /**
     * @var ConnectToUniquenessStorageInternalWorker
     */
    private $connectToUniquenessStorageInternalWorker;

    /**
     * @param ConnectToUniquenessStorageInternalWorker $connectToUniquenessStorageInternalWorker
     *
     * @Di\InjectParams({
     *     "connectToUniquenessStorageInternalWorker" = @Di\Inject("cubalider.unique.uniqueness.connect_to_storage_internal_worker")
     * })
     */
    public function __construct(ConnectToUniquenessStorageInternalWorker $connectToUniquenessStorageInternalWorker)
    {
        $this->connectToUniquenessStorageInternalWorker = $connectToUniquenessStorageInternalWorker;
    }

    /**
     * @return boolean
     */
    public function check()
    {
        return $this->connectToUniquenessStorageInternalWorker->connect()->find()
            ? true
            : false;
    }
}

<?php

namespace Muchacuba\InfoSms;

use Muchacuba\InfoSms\Profile\ConnectToStorageInternalWorker;
use Muchacuba\InfoSms\Profile\NonExistentUniquenessSharedException;
use JMS\DiExtraBundle\Annotation as Di;

/**
 * @author Yosmany Garcia <yosmanyga@gmail.com>
 * @author Nabel Hernandez <nabelhm@cubalider.com>
 *
 * @Di\Service()
 */
class DeleteProfileSharedWorker
{
    /**
     * @var ConnectToStorageInternalWorker
     */
    private $connectToStorageInternalWorker;

    /**
     * @param ConnectToStorageInternalWorker                 $connectToStorageInternalWorker
     *
     * @Di\InjectParams({
     *     "connectToStorageInternalWorker"                 = @Di\Inject("muchacuba.info_sms.profile.connect_to_storage_internal_worker"),
     *     "connectToBalanceOperationStorageInternalWorker" = @Di\Inject("muchacuba.info_sms.profile.balance.operation.connect_to_storage_internal_worker"),
     * })
     */
    public function __construct(
        ConnectToStorageInternalWorker $connectToStorageInternalWorker
    )
    {
        $this->connectToStorageInternalWorker = $connectToStorageInternalWorker;
    }

    /**
     * Deletes the profile with given uniqueness.
     *
     * @param string $uniqueness
     *
     * @throws NonExistentUniquenessSharedException
     */
    public function delete($uniqueness)
    {
        $result = $this->connectToStorageInternalWorker->connect()->remove([
            'uniqueness' => $uniqueness
        ]);

        if ($result['n'] == 0) {
            throw new NonExistentUniquenessSharedException($uniqueness);
        }
    }
}

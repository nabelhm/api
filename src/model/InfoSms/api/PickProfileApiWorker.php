<?php

namespace Muchacuba\InfoSms;

use Muchacuba\InfoSms\Profile\ConnectToStorageInternalWorker;
use Muchacuba\InfoSms\Profile\NonExistentUniquenessApiException;
use JMS\DiExtraBundle\Annotation as Di;

/**
 * @author Nabel Hernandez <nabelhm@cubalider.com>
 * @author Yosmany Garcia <yosmanyga@gmail.com
 *
 * @Di\Service()
 */
class PickProfileApiWorker
{
    /**
     * @var ConnectToStorageInternalWorker
     */
    private $connectToStorageInternalWorker;

    /**
     * @param ConnectToStorageInternalWorker $connectToStorageInternalWorker
     *
     * @Di\InjectParams({
     *     "connectToStorageInternalWorker" = @Di\Inject("muchacuba.info_sms.profile.connect_to_storage_internal_worker"),
     * })
     */
    public function __construct(ConnectToStorageInternalWorker $connectToStorageInternalWorker)
    {
        $this->connectToStorageInternalWorker = $connectToStorageInternalWorker;
    }

    /**
     * Picks the profile with given uniqueness.
     *
     * @param string $uniqueness
     *
     * @return array An array with the following keys:
     *               balance
     *
     * @throws NonExistentUniquenessApiException
     */
    public function pick($uniqueness)
    {
        $profile = $this->connectToStorageInternalWorker->connect()->findOne(
            [
                'uniqueness' => $uniqueness
            ],
            [
                '_id' => 0 // Exclude
            ]
        );

        if (!$profile) {
            throw new NonExistentUniquenessApiException();
        }

        return $profile;
    }
}

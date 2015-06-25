<?php

namespace Muchacuba\Authentication;

use Muchacuba\Authentication\Profile\ConnectToStorageInternalWorker;
use Muchacuba\Authentication\Profile\NonExistentUniquenessSharedException;
use JMS\DiExtraBundle\Annotation as Di;

/**
 * @author Yosmany Garcia <yosmanyga@gmail.com>
 *
 * @Di\Service()
 */
class PickProfileSharedWorker
{
    /**
     * @var ConnectToStorageInternalWorker
     */
    private $connectToStorageInternalWorker;

    /**
     * @param ConnectToStorageInternalWorker $connectToStorageInternalWorker
     *
     * @Di\InjectParams({
     *     "connectToStorageInternalWorker" = @Di\Inject("muchacuba.authentication.profile.connect_to_storage_internal_worker"),
     * })
     */
    function __construct(ConnectToStorageInternalWorker $connectToStorageInternalWorker)
    {
        $this->connectToStorageInternalWorker = $connectToStorageInternalWorker;
    }

    /**
     * Picks the profile with given uniqueness.
     *
     * @param string $uniqueness
     *
     * @return array
     *
     * @throws NonExistentUniquenessSharedException
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
            throw new NonExistentUniquenessSharedException($uniqueness);
        }

        return $profile;
    }
}
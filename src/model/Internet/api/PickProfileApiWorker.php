<?php

namespace Muchacuba\Internet;

use Muchacuba\Internet\Profile\ConnectToStorageInternalWorker;
use Muchacuba\Internet\Profile\NonExistentUniquenessApiException;
use JMS\DiExtraBundle\Annotation as Di;

/**
 * @author Yosmany Garcia <yosmanyga@gmail.com>
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
     *     "connectToStorageInternalWorker" = @Di\Inject("muchacuba.internet.profile.connect_to_storage_internal_worker")
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
     * @throws NonExistentUniquenessApiException
     */
    public function pick($uniqueness)
    {
        $profile = $this->connectToStorageInternalWorker->connect()->findOne([
            'uniqueness' => $uniqueness
        ]);

        if (!$profile) {
            throw new NonExistentUniquenessApiException();
        }

        return [
            'uniqueness' => $profile['uniqueness'],
            'email' => $profile['email']
        ];
    }
}

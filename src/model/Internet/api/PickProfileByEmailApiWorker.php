<?php

namespace Muchacuba\Internet;

use Muchacuba\Internet\Profile\ConnectToStorageInternalWorker;
use Muchacuba\Internet\Profile\NonExistentEmailApiException;
use JMS\DiExtraBundle\Annotation as Di;

/**
 * @author Yosmany Garcia <yosmanyga@gmail.com>
 *
 * @Di\Service()
 */
class PickProfileByEmailApiWorker
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
     * Picks the profile with given email.
     *
     * @param string $email
     *
     * @return array An array with the following keys:
     *               uniqueness, email
     *
     * @throws NonExistentEmailApiException
     */
    public function pick($email)
    {
        $profile = $this->connectToStorageInternalWorker->connect()
            ->findOne(
                [
                    'email' => $email
                ],
                [
                    '_id' => 0 // Exclude
                ]
            );

        if (!$profile) {
            throw new NonExistentEmailApiException();
        }

        return $profile;
    }
}

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
class DeleteProfileSharedWorker
{
    /**
     * @var ConnectToStorageInternalWorker
     */
    private $connectToStorageInternalWorker;

    /**
     * @param ConnectToStorageInternalWorker $connectToStorageInternalWorker
     *
     * @Di\InjectParams({
     *     "connectToStorageInternalWorker" = @Di\Inject("muchacuba.credit.profile.connect_to_storage_internal_worker"),
     * })
     */
    public function __construct(ConnectToStorageInternalWorker $connectToStorageInternalWorker)
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
        $result = $this->connectToStorageInternalWorker->connect()->remove(array(
            'uniqueness' => $uniqueness,
        ));

        if ($result['n'] == 0) {
            throw new NonExistentUniquenessSharedException($uniqueness);
        }
    }
}
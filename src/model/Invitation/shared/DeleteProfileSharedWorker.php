<?php

namespace Muchacuba\Invitation;

use Muchacuba\Invitation\AssignedCard\ConnectToStorageInternalWorker as ConnectToAssignedCardStorageInternalWorker;
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
     * @var ConnectToAssignedCardStorageInternalWorker
     */
    private $connectToAssignedCardStorageInternalWorker;

    /**
     * @param ConnectToAssignedCardStorageInternalWorker $connectToAssignedCardStorageInternalWorker
     *
     * @Di\InjectParams({
     *     "connectToAssignedCardStorageInternalWorker" = @Di\Inject("muchacuba.invitation.assigned_card.connect_to_storage_internal_worker")
     * })
     */
    function __construct(ConnectToAssignedCardStorageInternalWorker $connectToAssignedCardStorageInternalWorker)
    {
        $this->connectToAssignedCardStorageInternalWorker = $connectToAssignedCardStorageInternalWorker;
    }

    /**
     * Deletes the profile with given uniqueness.
     *
     * @param string $uniqueness
     */
    public function delete($uniqueness)
    {
        $this->connectToAssignedCardStorageInternalWorker->connect()->remove([
            'uniqueness' => $uniqueness
        ]);
    }
}
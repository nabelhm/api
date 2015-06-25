<?php

namespace Muchacuba\Invitation;

use Muchacuba\Invitation\Card\AlreadyConsumedSharedException;
use Muchacuba\Invitation\Card\ConnectToStorageInternalWorker;
use Muchacuba\Invitation\Card\NonExistentCodeInternalException;
use JMS\DiExtraBundle\Annotation as Di;
use Muchacuba\Invitation\Card\NonExistentCodeSharedException;

/**
 * @author Yosmany Garcia <yosmanyga@gmail.com>
 *
 * @Di\Service()
 */
class ConsumeCardSharedWorker
{
    /**
     * @var PickCardSharedWorker
     */
    private $pickCardSharedWorker;

    /**
     * @var ConnectToStorageInternalWorker
     */
    private $connectToStorageInternalWorker;

    /**
     * @param PickCardSharedWorker           $pickCardSharedWorker
     * @param ConnectToStorageInternalWorker $connectToStorageInternalWorker
     *
     * @Di\InjectParams({
     *     "pickCardSharedWorker"           = @Di\Inject("muchacuba.invitation.pick_card_shared_worker"),
     *     "connectToStorageInternalWorker" = @Di\Inject("muchacuba.invitation.card.connect_to_storage_internal_worker")
     * })
     */
    function __construct(
        PickCardSharedWorker $pickCardSharedWorker,
        ConnectToStorageInternalWorker $connectToStorageInternalWorker
    )
    {
        $this->pickCardSharedWorker = $pickCardSharedWorker;
        $this->connectToStorageInternalWorker = $connectToStorageInternalWorker;
    }

    /**
     * Consumes card with given code.
     *
     * @param string $code
     *
     * @throws NonExistentCodeSharedException
     * @throws AlreadyConsumedSharedException
     */
    public function consume($code)
    {
        try {
            $card = $this->pickCardSharedWorker->pick($code);
        } catch (NonExistentCodeInternalException $e) {
            throw new NonExistentCodeSharedException();
        }

        if ($card['consumed']) {
            throw new AlreadyConsumedSharedException();
        }

        $this->connectToStorageInternalWorker->connect()->update(
            array('code' => $code),
            array('$set' => array(
                'consumed' => true
            ))
        );
    }
}
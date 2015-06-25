<?php

namespace Muchacuba\Invitation;

use Muchacuba\Invitation\Card\NonExistentCodeInternalException;
use JMS\DiExtraBundle\Annotation as Di;
use Muchacuba\Invitation\Card\NonExistentCodeSharedException;

/**
 * @author Yosmany Garcia <yosmanyga@gmail.com>
 *
 * @Di\Service()
 */
class PickCardSharedWorker
{
    /**
     * @var PickCardInternalWorker
     */
    private $pickCardInternalWorker;

    /**
     * @param PickCardInternalWorker $pickCardInternalWorker
     *
     * @Di\InjectParams({
     *     "pickCardInternalWorker" = @Di\Inject("muchacuba.invitation.pick_card_internal_worker"),
     * })
     */
    function __construct(PickCardInternalWorker $pickCardInternalWorker)
    {
        $this->pickCardInternalWorker = $pickCardInternalWorker;
    }

    /**
     * Picks the card with given code.
     *
     * @param string $code
     *
     * @return array An array with the following keys:
     *               code, role and consumed.
     *
     * @throws NonExistentCodeSharedException
     */
    public function pick($code)
    {
        try {
            return $this->pickCardInternalWorker->pick($code);
        } catch (NonExistentCodeInternalException $e) {
            throw new NonExistentCodeSharedException();
        }
    }
}

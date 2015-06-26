<?php

namespace Muchacuba\Invitation;

use Muchacuba\Invitation\Profile\InvalidAmountApiException;
use Muchacuba\Invitation\Profile\NonExistentRoleApiException;
use Muchacuba\Privilege\PickRoleInternalWorker;
use Muchacuba\Privilege\Role\NonExistentCodeInternalException as NonExistentRoleInternalException;
use JMS\DiExtraBundle\Annotation as Di;

/**
 * @author Yosmany Garcia <yosmanyga@gmail.com>
 * @author Nabel Hernandez <nabelhm@cubalider.com>
 *
 * @Di\Service()
 */
class GiveCardsTestWorker
{
    /**
     * @var PickRoleInternalWorker
     */
    private $pickRoleInternalWorker;

    /**
     * @var GenerateCardsInternalWorker
     */
    private $generateCardsInternalWorker;

    /**
     * @var AssignCardsInternalWorker
     */
    private $assignCardsInternalWorker;

    /**
     * @param PickRoleInternalWorker      $pickRoleInternalWorker
     * @param GenerateCardsInternalWorker $generateCardsInternalWorker
     * @param AssignCardsInternalWorker   $assignCardsInternalWorker
     *
     * @Di\InjectParams({
     *     "pickRoleInternalWorker"      = @Di\Inject("muchacuba.privilege.pick_role_internal_worker"),
     *     "generateCardsInternalWorker" = @Di\Inject("muchacuba.invitation.generate_cards_internal_worker"),
     *     "assignCardsInternalWorker"   = @Di\Inject("muchacuba.invitation.assign_cards_internal_worker")
     * })
     */
    function __construct(
        PickRoleInternalWorker $pickRoleInternalWorker,
        GenerateCardsInternalWorker $generateCardsInternalWorker,
        AssignCardsInternalWorker $assignCardsInternalWorker
    )
    {
        $this->pickRoleInternalWorker = $pickRoleInternalWorker;
        $this->generateCardsInternalWorker = $generateCardsInternalWorker;
        $this->assignCardsInternalWorker = $assignCardsInternalWorker;
    }

    /**
     * Gives to given uniqueness, the given amount of cards of given role.
     *
     * @param string $uniqueness
     * @param string $role
     * @param int    $amount
     *
     * @throws NonExistentRoleApiException
     * @throws InvalidAmountApiException
     */
    public function give($uniqueness, $role, $amount)
    {
        try {
            $this->pickRoleInternalWorker->pick($role);
        } catch (NonExistentRoleInternalException $e) {
            throw new NonExistentRoleApiException();
        }

        if (!ctype_digit((string) $amount)) {
            throw new InvalidAmountApiException();
        }

        $cards = $this->generateCardsInternalWorker->generate($role, $amount);

        $this->assignCardsInternalWorker->assign($uniqueness, $cards);
    }
}
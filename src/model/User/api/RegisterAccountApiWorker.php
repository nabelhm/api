<?php

namespace Muchacuba\User;

use Muchacuba\Invitation\Card\NonExistentCodeSharedException;
use Muchacuba\Invitation\PickCardSharedWorker;
use Muchacuba\Invitation\ConsumeCardSharedWorker;
use Muchacuba\User\Account\AlreadyConsumedInvitationApiException;
use Muchacuba\User\Account\EmptyPasswordApiException;
use Muchacuba\User\Account\EmptyPasswordInternalException;
use Muchacuba\User\Account\ExistentUsernameApiException;
use Muchacuba\User\Account\ExistentUsernameInternalException;
use Muchacuba\User\Account\InvalidUsernameApiException;
use Muchacuba\User\Account\InvalidUsernameInternalException;
use Muchacuba\User\Account\NonExistentInvitationApiException;
use JMS\DiExtraBundle\Annotation as Di;

/**
 * @author Yosmany Garcia <yosmanyga@gmail.com>
 *
 * @Di\Service()
 */
class RegisterAccountApiWorker
{
    /**
     * @var PickCardSharedWorker
     */
    private $pickCardSharedWorker;

    /**
     * @var ConsumeCardSharedWorker
     */
    private $consumeCardSharedWorker;

    /**
     * @var CreateAccountInternalWorker
     */
    private $createAccountInternalWorker;

    /**
     * @param PickCardSharedWorker        $pickCardSharedWorker
     * @param ConsumeCardSharedWorker     $consumeCardSharedWorker
     * @param CreateAccountInternalWorker $createAccountInternalWorker
     *
     * @Di\InjectParams({
     *     "pickCardSharedWorker"        = @Di\Inject("muchacuba.invitation.pick_card_shared_worker"),
     *     "consumeCardSharedWorker"     = @Di\Inject("muchacuba.invitation.consume_card_shared_worker"),
     *     "createAccountInternalWorker" = @Di\Inject("muchacuba.user.create_account_internal_worker")
     * })
     */
    function __construct(
        PickCardSharedWorker $pickCardSharedWorker,
        ConsumeCardSharedWorker $consumeCardSharedWorker,
        CreateAccountInternalWorker $createAccountInternalWorker
    )
    {
        $this->pickCardSharedWorker = $pickCardSharedWorker;
        $this->consumeCardSharedWorker = $consumeCardSharedWorker;
        $this->createAccountInternalWorker = $createAccountInternalWorker;
    }

    /**
     * Registers a user account using an invitation that assigns a role.
     * The username can be an email or a mobile.
     *
     * @param string $invitation
     * @param string $username
     * @param string $password
     *
     * @return string The already created uniqueness.
     *
     * @throws NonExistentInvitationApiException
     * @throws AlreadyConsumedInvitationApiException
     * @throws EmptyPasswordApiException
     * @throws InvalidUsernameApiException
     * @throws ExistentUsernameApiException
     */
    public function register($invitation, $username, $password)
    {
        try {
            $card = $this->pickCardSharedWorker->pick($invitation);
        } catch (NonExistentCodeSharedException $e) {
            throw new NonExistentInvitationApiException();
        }

        if ($card['consumed']) {
            throw new AlreadyConsumedInvitationApiException();
        }

        try {
            $uniqueness = $this->createAccountInternalWorker->create(
                $username,
                $password,
                [$card['role']]
            );
        } catch (EmptyPasswordInternalException $e) {
            throw new EmptyPasswordApiException();
        } catch (InvalidUsernameInternalException $e) {
            throw new InvalidUsernameApiException();
        } catch (ExistentUsernameInternalException $e) {
            throw new ExistentUsernameApiException();
        }

        $this->consumeCardSharedWorker->consume($invitation);

        return $uniqueness;
    }
}
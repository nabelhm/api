<?php

namespace Muchacuba\User;

use Cubalider\Unique\DeleteUniquenessSharedWorker;
use Muchacuba\Authentication\CreateProfileSharedWorker as CreateAuthenticationProfileSharedWorker;
use Muchacuba\Internet\CreateProfileSharedWorker as CreateInternetProfileSharedWorker;
use Muchacuba\Internet\Profile\ExistentEmailSharedException;
use Muchacuba\Internet\Profile\InvalidEmailSharedException;
use Muchacuba\Mobile\CreateProfileSharedWorker as CreateMobileProfileSharedWorker;
use Muchacuba\Mobile\Profile\ExistentNumberSharedException;
use Muchacuba\Credit\CreateProfileSharedWorker as CreateCreditProfileSharedWorker;
use Muchacuba\RechargeCard\CreateProfileSharedWorker as CreateRechargeCardProfileSharedWorker;
use Muchacuba\InfoSms\CreateProfileSharedWorker as CreateInfoSmsProfileSharedWorker;
use Muchacuba\Mobile\Profile\InvalidNumberSharedException;
use Muchacuba\Privilege\CreateProfileSharedWorker as CreatePrivilegeProfileSharedWorker;
use Muchacuba\Invitation\CreateProfileSharedWorker as CreateInvitationProfileSharedWorker;
use JMS\DiExtraBundle\Annotation as Di;
use Muchacuba\User\Account\EmptyPasswordInternalException;
use Muchacuba\User\Account\ExistentUsernameInternalException;
use Muchacuba\User\Account\InvalidUsernameInternalException;

/**
 * @author Yosmany Garcia <yosmanyga@gmail.com>
 *
 * @Di\Service()
 */
class CreateProfilesInternalWorker
{
    /**
     * @var DeleteUniquenessSharedWorker
     */
    private $deleteUniquenessSharedWorker;

    /**
     * @var CreateMobileProfileSharedWorker
     */
    private $createMobileProfileSharedWorker;

    /**
     * @var CreateInternetProfileSharedWorker
     */
    private $createInternetProfileSharedWorker;

    /**
     * @var CreatePrivilegeProfileSharedWorker
     */
    private $createPrivilegeProfileSharedWorker;

    /**
     * @var CreateInvitationProfileSharedWorker
     */
    private $createInvitationProfileSharedWorker;
    
    /**
     * @var CreateAuthenticationProfileSharedWorker
     */
    private $createAuthenticationProfileSharedWorker;

    /**
     * @var CreateCreditProfileSharedWorker
     */
    private $createCreditProfileSharedWorker;

    /**
     * @var CreateRechargeCardProfileSharedWorker
     */
    private $createRechargeCardProfileSharedWorker;

    /*
     * @var CreateInfoSmsProfileSharedWorker
     */
    private $createInfoSmsProfileSharedWorker;

    /**
     * @param DeleteUniquenessSharedWorker            $deleteUniquenessSharedWorker
     * @param CreateMobileProfileSharedWorker         $createMobileProfileSharedWorker
     * @param CreateInternetProfileSharedWorker       $createInternetProfileSharedWorker
     * @param CreatePrivilegeProfileSharedWorker      $createPrivilegeProfileSharedWorker
     * @param CreateInvitationProfileSharedWorker     $createInvitationProfileSharedWorker
     * @param CreateAuthenticationProfileSharedWorker $createAuthenticationProfileSharedWorker
     * @param CreateCreditProfileSharedWorker         $createCreditProfileSharedWorker
     * @param CreateRechargeCardProfileSharedWorker   $createRechargeCardProfileSharedWorker
     * @param CreateInfoSmsProfileSharedWorker        $createInfoSmsProfileSharedWorker
     *
     * @Di\InjectParams({
     *     "deleteUniquenessSharedWorker"            = @Di\Inject("cubalider.unique.delete_uniqueness_shared_worker"),
     *     "createMobileProfileSharedWorker"         = @Di\Inject("muchacuba.mobile.create_profile_shared_worker"),
     *     "createInternetProfileSharedWorker"       = @Di\Inject("muchacuba.internet.create_profile_shared_worker"),
     *     "createPrivilegeProfileSharedWorker"      = @Di\Inject("muchacuba.privilege.create_profile_shared_worker"),
     *     "createInvitationProfileSharedWorker"     = @Di\Inject("muchacuba.invitation.create_profile_shared_worker"),
     *     "createAuthenticationProfileSharedWorker" = @Di\Inject("muchacuba.authentication.create_profile_shared_worker"),
     *     "createCreditProfileSharedWorker"         = @Di\Inject("muchacuba.credit.create_profile_shared_worker"),
     *     "createRechargeCardProfileSharedWorker"   = @Di\Inject("muchacuba.recharge_card.create_profile_shared_worker"),
     *     "createInfoSmsProfileSharedWorker"        = @Di\Inject("muchacuba.info_sms.create_profile_shared_worker")
     * })
     */
    function __construct(
        DeleteUniquenessSharedWorker $deleteUniquenessSharedWorker,
        CreateMobileProfileSharedWorker $createMobileProfileSharedWorker,
        CreateInternetProfileSharedWorker $createInternetProfileSharedWorker,
        CreatePrivilegeProfileSharedWorker $createPrivilegeProfileSharedWorker,
        CreateInvitationProfileSharedWorker $createInvitationProfileSharedWorker,
        CreateAuthenticationProfileSharedWorker $createAuthenticationProfileSharedWorker,
        CreateCreditProfileSharedWorker $createCreditProfileSharedWorker,
        CreateRechargeCardProfileSharedWorker $createRechargeCardProfileSharedWorker,
        CreateInfoSmsProfileSharedWorker $createInfoSmsProfileSharedWorker
    )
    {
        $this->deleteUniquenessSharedWorker = $deleteUniquenessSharedWorker;
        $this->createMobileProfileSharedWorker = $createMobileProfileSharedWorker;
        $this->createInternetProfileSharedWorker = $createInternetProfileSharedWorker;
        $this->createPrivilegeProfileSharedWorker = $createPrivilegeProfileSharedWorker;
        $this->createInvitationProfileSharedWorker = $createInvitationProfileSharedWorker;
        $this->createAuthenticationProfileSharedWorker = $createAuthenticationProfileSharedWorker;
        $this->createCreditProfileSharedWorker = $createCreditProfileSharedWorker;
        $this->createRechargeCardProfileSharedWorker = $createRechargeCardProfileSharedWorker;
        $this->createInfoSmsProfileSharedWorker = $createInfoSmsProfileSharedWorker;
    }

    /**
     * Creates a user account.
     * The username can be an email or a mobile.
     *
     * @param string   $uniqueness
     * @param string   $username
     * @param string   $password
     * @param string[] $roles
     *
     * @throws EmptyPasswordInternalException
     * @throws InvalidUsernameInternalException
     * @throws ExistentUsernameInternalException
     */
    public function create($uniqueness, $username, $password, $roles)
    {
        if ($password === '') {
            throw new EmptyPasswordInternalException();
        }

        try {
            $this->createMobileProfileSharedWorker->create($uniqueness, $username);
        } catch (InvalidNumberSharedException $e) {
            try {
                $this->createInternetProfileSharedWorker->create($uniqueness, $username);
            } catch (InvalidEmailSharedException $e) {
                $this->deleteUniquenessSharedWorker->delete($uniqueness);

                throw new InvalidUsernameInternalException();
            } catch (ExistentEmailSharedException $e) {
                $this->deleteUniquenessSharedWorker->delete($uniqueness);

                throw new ExistentUsernameInternalException();
            }
        } catch (ExistentNumberSharedException $e) {
            $this->deleteUniquenessSharedWorker->delete($uniqueness);

            throw new ExistentUsernameInternalException();
        }

        $this->createPrivilegeProfileSharedWorker->create($uniqueness, $roles);

        $this->createInvitationProfileSharedWorker->create($uniqueness);

        $this->createAuthenticationProfileSharedWorker->create($uniqueness, $password);

        $this->createCreditProfileSharedWorker->create($uniqueness, 0);

        $this->createRechargeCardProfileSharedWorker->create($uniqueness, 0, []);

        $this->createInfoSmsProfileSharedWorker->create($uniqueness, 0);
    }
}
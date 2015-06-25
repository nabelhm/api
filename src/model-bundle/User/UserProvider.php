<?php

namespace Muchacuba\ModelBundle\User;

use Muchacuba\Internet\Profile\NonExistentEmailApiException as NonExistentInternetProfileApiException;
use Muchacuba\Mobile\Profile\NonExistentNumberApiException as NonExistentMobileProfileApiException;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Core\Exception\UsernameNotFoundException;
use Symfony\Component\Security\Core\User\UserInterface as SecurityUserInterface;
use Muchacuba\Internet\PickProfileByEmailApiWorker as PickInternetProfileByEmailApiWorker;
use Muchacuba\Mobile\PickProfileByNumberApiWorker as PickMobileProfileByNumberApiWorker;
use Muchacuba\Privilege\PickProfileApiWorker as PickPrivilegeProfileSharedWorker;
use Muchacuba\Authentication\PickProfileSharedWorker as PickAuthenticationProfileSharedWorker;
use JMS\DiExtraBundle\Annotation as Di;

/**
 * @author Yosmany Garcia <yosmanyga@gmail.com>
 *
 * @Di\Service()
 */
class UserProvider implements UserProviderInterface
{
    /**
     * @var PickMobileProfileByNumberApiWorker
     */
    private $pickMobileProfileByNumberApiWorker;

    /**
     * @var PickInternetProfileByEmailApiWorker
     */
    private $pickInternetProfileByEmailApiWorker;

    /**
     * @var PickPrivilegeProfileSharedWorker
     */
    private $pickPrivilegeProfileSharedWorker;

    /**
     * @var PickAuthenticationProfileSharedWorker
     */
    private $pickAuthenticationProfileSharedWorker;

    /**
     * @param PickMobileProfileByNumberApiWorker    $pickMobileProfileByNumberApiWorker
     * @param PickInternetProfileByEmailApiWorker   $pickInternetProfileByEmailApiWorker
     * @param PickPrivilegeProfileSharedWorker      $pickPrivilegeProfileSharedWorker
     * @param PickAuthenticationProfileSharedWorker $pickAuthenticationProfileSharedWorker
     * 
     * @Di\InjectParams({
     *     "pickMobileProfileByNumberApiWorker"    = @Di\Inject("muchacuba.mobile.pick_profile_by_number_api_worker"),
     *     "pickInternetProfileByEmailApiWorker"   = @Di\Inject("muchacuba.internet.pick_profile_by_email_api_worker"),
     *     "pickPrivilegeProfileSharedWorker"      = @Di\Inject("muchacuba.privilege.pick_profile_api_worker"),
     *     "pickAuthenticationProfileSharedWorker" = @Di\Inject("muchacuba.authentication.pick_profile_shared_worker"),
     * })
     */
    function __construct(
        PickMobileProfileByNumberApiWorker $pickMobileProfileByNumberApiWorker,
        PickInternetProfileByEmailApiWorker $pickInternetProfileByEmailApiWorker,
        PickPrivilegeProfileSharedWorker $pickPrivilegeProfileSharedWorker,
        PickAuthenticationProfileSharedWorker $pickAuthenticationProfileSharedWorker
    )
    {
        $this->pickMobileProfileByNumberApiWorker = $pickMobileProfileByNumberApiWorker;
        $this->pickInternetProfileByEmailApiWorker = $pickInternetProfileByEmailApiWorker;
        $this->pickPrivilegeProfileSharedWorker = $pickPrivilegeProfileSharedWorker;
        $this->pickAuthenticationProfileSharedWorker = $pickAuthenticationProfileSharedWorker;
    }

    /**
     * {@inheritDoc}
     */
    public function loadUserByUsername($username)
    {
        try {
            $usernameProfile = $this->pickInternetProfileByEmailApiWorker->pick($username);
        } catch (NonExistentInternetProfileApiException $e) {
            try {
                $usernameProfile = $this->pickMobileProfileByNumberApiWorker->pick($username);
            } catch (NonExistentMobileProfileApiException $e) {
                throw new UsernameNotFoundException();
            }
        }

        $authenticationProfile = $this->pickAuthenticationProfileSharedWorker->pick($usernameProfile['uniqueness']);

        $privilegeProfile = $this->pickPrivilegeProfileSharedWorker->pick($usernameProfile['uniqueness']);

        return new User(
            $usernameProfile['uniqueness'],
            $authenticationProfile['hash'],
            $authenticationProfile['salt'],
            $privilegeProfile['roles']
        );
    }

    /**
     * {@inheritDoc}
     */
    public function refreshUser(SecurityUserInterface $user)
    {
        if (!$user instanceof User) {
            throw new UnsupportedUserException(sprintf('Instances of "%s" are not supported.', get_class($user)));
        }

        $authenticationProfile = $this->pickAuthenticationProfileSharedWorker->pick($user->getUsername());

        $privilegeProfile = $this->pickPrivilegeProfileSharedWorker->pick($authenticationProfile['uniqueness']);

        return new User(
            $user->getUsername(),
            $authenticationProfile['hash'],
            $authenticationProfile['salt'],
            $privilegeProfile['roles']
        );
    }

    /**
     * {@inheritDoc}
     */
    public function supportsClass($class)
    {
        return $class === 'Muchacuba\ModelBundle\User\User';
    }
}

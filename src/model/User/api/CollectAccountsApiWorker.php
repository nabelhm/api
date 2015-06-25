<?php

namespace Muchacuba\User;

use Cubalider\Unique\CollectUniquenessesSharedWorker;
use Muchacuba\Mobile\PickProfileApiWorker as PickMobileByNumberProfileApiWorker;
use Muchacuba\Internet\PickProfileApiWorker as PickInternetProfileApiWorker;
use Muchacuba\Privilege\PickProfileApiWorker as PickPrivilegeProfileApiWorker;
use Muchacuba\Internet\Profile\NonExistentUniquenessApiException as NonExistentInternetUniquenessApiException;
use Muchacuba\Mobile\Profile\NonExistentUniquenessApiException as NonExistentMobileUniquenessApiException;
use JMS\DiExtraBundle\Annotation as Di;

/**
 * @author Yosmany Garcia <yosmanyga@gmail.com>
 *
 * @Di\Service()
 */
class CollectAccountsApiWorker
{
    /**
     * @var CollectUniquenessesSharedWorker
     */
    private $collectUniquenessesSharedWorker;

    /**
     * @var PickMobileByNumberProfileApiWorker
     */
    private $pickMobileByNumberProfileApiWorker;

    /**
     * @var PickInternetProfileApiWorker
     */
    private $pickInternetProfileApiWorker;

    /**
     * @var PickPrivilegeProfileApiWorker
     */
    private $pickPrivilegeProfileApiWorker;

    /**
     * @param CollectUniquenessesSharedWorker    $collectUniquenessesSharedWorker
     * @param PickMobileByNumberProfileApiWorker $pickMobileByNumberProfileApiWorker
     * @param PickInternetProfileApiWorker       $pickInternetProfileApiWorker
     * @param PickPrivilegeProfileApiWorker      $pickPrivilegeProfileApiWorker
     *
     * @Di\InjectParams({
     *     "collectUniquenessesSharedWorker"    = @Di\Inject("cubalider.unique.collect_uniquenesses_shared_worker"),
     *     "pickMobileByNumberProfileApiWorker" = @Di\Inject("muchacuba.mobile.pick_profile_api_worker"),
     *     "pickInternetProfileApiWorker"       = @Di\Inject("muchacuba.internet.pick_profile_api_worker"),
     *     "pickPrivilegeProfileApiWorker"      = @Di\Inject("muchacuba.privilege.pick_profile_api_worker")
     * })
     */
    function __construct(
        CollectUniquenessesSharedWorker $collectUniquenessesSharedWorker,
        PickMobileByNumberProfileApiWorker $pickMobileByNumberProfileApiWorker,
        PickInternetProfileApiWorker $pickInternetProfileApiWorker,
        PickPrivilegeProfileApiWorker $pickPrivilegeProfileApiWorker
    )
    {
        $this->collectUniquenessesSharedWorker = $collectUniquenessesSharedWorker;
        $this->pickMobileByNumberProfileApiWorker = $pickMobileByNumberProfileApiWorker;
        $this->pickInternetProfileApiWorker = $pickInternetProfileApiWorker;
        $this->pickPrivilegeProfileApiWorker = $pickPrivilegeProfileApiWorker;
    }

    /**
     * Collects accounts.
     *
     * @return array An array of accounts with keys uniqueness, mobile, email
     *               and roles.
     */
    public function collect()
    {
        $uniquenesses = $this->collectUniquenessesSharedWorker->collect();

        $accounts = [];
        foreach ($uniquenesses as $uniqueness) {
            try {
                $mobileProfile = $this->pickMobileByNumberProfileApiWorker->pick($uniqueness['id']);
                $mobile = $mobileProfile['number'];
            } catch (NonExistentMobileUniquenessApiException $e) {
                $mobile = '';
            }
            
            try {
                $internetProfile = $this->pickInternetProfileApiWorker->pick($uniqueness['id']);
                $email = $internetProfile['email'];
            } catch (NonExistentInternetUniquenessApiException $e) {
                $email = '';
            }

            $privilegeProfile = $this->pickPrivilegeProfileApiWorker->pick($uniqueness['id']);

            $accounts[] = [
                'uniqueness' => $uniqueness['id'],
                'mobile' => $mobile,
                'email' => $email,
                'roles' => $privilegeProfile['roles']
            ];
        }

        return $accounts;
    }
}
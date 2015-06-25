<?php

namespace Muchacuba\User;

use Cubalider\Unique\CreateUniquenessSharedWorker;
use Muchacuba\User\Account\EmptyPasswordInternalException;
use Muchacuba\User\Account\ExistentUsernameInternalException;
use Muchacuba\User\Account\InvalidUsernameInternalException;
use Muchacuba\User\Account\LogOperationInternalWorker;
use JMS\DiExtraBundle\Annotation as Di;

/**
 * @author Yosmany Garcia <yosmanyga@gmail.com>
 *
 * @Di\Service()
 */
class CreateAccountInternalWorker
{
    /**
     * @var CreateUniquenessSharedWorker
     */
    private $createUniquenessSharedWorker;

    /**
     * @var CreateProfilesInternalWorker
     */
    private $createProfilesInternalWorker;

    /**
     * @var LogOperationInternalWorker
     */
    private $logOperationInternalWorker;

    /**
     * @param CreateUniquenessSharedWorker $createUniquenessSharedWorker
     * @param CreateProfilesInternalWorker $createProfilesInternalWorker
     * @param LogOperationInternalWorker   $logOperationInternalWorker
     *
     * @Di\InjectParams({
     *     "createUniquenessSharedWorker" = @Di\Inject("cubalider.unique.create_uniqueness_shared_worker"),
     *     "createProfilesInternalWorker" = @Di\Inject("muchacuba.user.create_profiles_internal_worker"),
     *     "logOperationInternalWorker"   = @Di\Inject("muchacuba.user.account.log_operation_internal_worker")
     * })
     */
    function __construct(
        CreateUniquenessSharedWorker $createUniquenessSharedWorker,
        CreateProfilesInternalWorker $createProfilesInternalWorker,
        LogOperationInternalWorker $logOperationInternalWorker
    )
    {
        $this->createUniquenessSharedWorker = $createUniquenessSharedWorker;
        $this->createProfilesInternalWorker = $createProfilesInternalWorker;
        $this->logOperationInternalWorker = $logOperationInternalWorker;
    }

    /**
     * Creates a user account.
     * The username can be an email or a mobile.
     *
     * @param string   $username
     * @param string   $password
     * @param string[] $roles
     *
     * @return string the already created uniqueness
     *
     * @throws EmptyPasswordInternalException
     * @throws InvalidUsernameInternalException
     * @throws ExistentUsernameInternalException
     */
    public function create($username, $password, $roles)
    {
        $uniqueness = $this->createUniquenessSharedWorker->create();

        try {
            $this->createProfilesInternalWorker->create(
                $uniqueness,
                $username,
                $password,
                $roles
            );
        } catch (EmptyPasswordInternalException $e) {
            throw $e;
        } catch (InvalidUsernameInternalException $e) {
            throw $e;
        } catch (ExistentUsernameInternalException $e) {
            throw $e;
        }

        $this->logOperationInternalWorker->logCreation($uniqueness);

        return $uniqueness;
    }
}
<?php

namespace Muchacuba\User;

use Cubalider\Unique\CreateUniquenessTestWorker;
use JMS\DiExtraBundle\Annotation as Di;
use Muchacuba\User\Account\EmptyPasswordInternalException;
use Muchacuba\User\Account\ExistentUsernameInternalException;
use Muchacuba\User\Account\InvalidUsernameInternalException;

/**
 * @author Yosmany Garcia <yosmanyga@gmail.com>
 *
 * @Di\Service()
 */
class CreateAccountTestWorker
{
    /**
     * @var CreateUniquenessTestWorker
     */
    private $createUniquenessTestWorker;

    /**
     * @var CreateProfilesInternalWorker
     */
    private $createProfilesInternalWorker;

    /**
     * @param CreateUniquenessTestWorker   $createUniquenessTestWorker
     * @param CreateProfilesInternalWorker $createProfilesInternalWorker
     *
     * @Di\InjectParams({
     *     "createUniquenessTestWorker"   = @Di\Inject("cubalider.unique.create_uniqueness_test_worker"),
     *     "createProfilesInternalWorker" = @Di\Inject("muchacuba.user.create_profiles_internal_worker")
     * })
     */
    function __construct(
        CreateUniquenessTestWorker $createUniquenessTestWorker,
        CreateProfilesInternalWorker $createProfilesInternalWorker
    )
    {
        $this->createUniquenessTestWorker = $createUniquenessTestWorker;
        $this->createProfilesInternalWorker = $createProfilesInternalWorker;
    }

    /**
     * Creates a user account.
     * The username can be an email or a mobile.
     *
     * @param string   $id
     * @param string   $username
     * @param string   $password
     * @param string[] $roles
     *
     * @throws EmptyPasswordInternalException
     * @throws InvalidUsernameInternalException
     * @throws ExistentUsernameInternalException
     */
    public function create($id, $username, $password, $roles)
    {
        $this->createUniquenessTestWorker->create($id);

        $this->createProfilesInternalWorker->create(
            $id,
            $username,
            $password,
            $roles
        );
    }
}
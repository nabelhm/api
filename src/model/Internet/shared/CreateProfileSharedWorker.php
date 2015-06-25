<?php

namespace Muchacuba\Internet;

use Muchacuba\Internet\Profile\ConnectToStorageInternalWorker;
use Muchacuba\Internet\Profile\ExistentEmailSharedException;
use Muchacuba\Internet\Profile\ExistentUniquenessSharedException;
use Muchacuba\Internet\Profile\InvalidEmailSharedException;
use Respect\Validation\Validator;
use JMS\DiExtraBundle\Annotation as Di;

/**
 * @author Yosmany Garcia <yosmanyga@gmail.com>
 * @author Nabel Hernandez <nabelhm@cubalider.com>
 *
 * @Di\Service()
 */
class CreateProfileSharedWorker
{
    /**
     * @var ConnectToStorageInternalWorker
     */
    private $connectToStorageInternalWorker;

    /**
     * @param ConnectToStorageInternalWorker $connectToStorageInternalWorker
     *
     * @Di\InjectParams({
     *     "connectToStorageInternalWorker" = @Di\Inject("muchacuba.internet.profile.connect_to_storage_internal_worker")
     * })
     */
    function __construct(
        ConnectToStorageInternalWorker $connectToStorageInternalWorker
    )
    {
        $this->connectToStorageInternalWorker = $connectToStorageInternalWorker;
    }

    /**
     * Creates a profile.
     *
     * @param string $uniqueness
     * @param string $email
     *
     * @throws InvalidEmailSharedException
     * @throws ExistentEmailSharedException
     * @throws ExistentUniquenessSharedException
     * @throws \MongoCursorException
     */
    public function create($uniqueness, $email)
    {
        if (!Validator::email()->validate($email)) {
            throw new InvalidEmailSharedException();
        }

        try {
            $this->connectToStorageInternalWorker->connect()->insert([
                'uniqueness' => $uniqueness,
                'email' => $email
            ]);
        } catch (\MongoCursorException $e) {
            if (11000 == $e->getCode()) {
                if (strpos($e->getMessage(), '$email_1') !== false) {
                    throw new ExistentEmailSharedException();
                }

                throw new ExistentUniquenessSharedException();
            }

            throw $e;
        }
    }
}
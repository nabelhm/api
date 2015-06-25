<?php

namespace Muchacuba\Authentication;

use Cubalider\Security\Encoder;
use Cubalider\Security\SaltGenerator;
use Muchacuba\Authentication\Profile\ConnectToStorageInternalWorker;
use Muchacuba\Authentication\Profile\ExistentUniquenessSharedException;
use JMS\DiExtraBundle\Annotation as Di;

/**
 * @author Yosmany Garcia <yosmanyga@gmail.com>
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
     * @var SaltGenerator
     */
    private $generator;

    /**
     * @var Encoder
     */
    private $encoder;

    /**
     * @param ConnectToStorageInternalWorker $connectToStorageInternalWorker
     * @param SaltGenerator                  $generator
     * @param Encoder                        $encoder
     *
     * @Di\InjectParams({
     *     "connectToStorageInternalWorker" = @Di\Inject("muchacuba.authentication.profile.connect_to_storage_internal_worker"),
     *     "generator"                      = @Di\Inject("cubalider.security.ordinary_salt_generator"),
     *     "encoder"                        = @Di\Inject("cubalider.security.hash_encoder")
     * })
     */
    function __construct(
        ConnectToStorageInternalWorker $connectToStorageInternalWorker,
        SaltGenerator $generator,
        Encoder $encoder
    )
    {
        $this->connectToStorageInternalWorker = $connectToStorageInternalWorker;
        $this->generator = $generator;
        $this->encoder = $encoder;
    }

    /**
     * Creates a profile.
     *
     * @param string $uniqueness
     * @param string $password
     *
     * @throws ExistentUniquenessSharedException
     * @throws \MongoCursorException
     */
    public function create($uniqueness, $password)
    {
        $salt = $this->generator->generate();
        $hash = $this->encoder->encode($password, $salt);

        try {
            $this->connectToStorageInternalWorker->connect()->insert([
                'uniqueness' => $uniqueness,
                'salt' => $salt,
                'hash' => $hash
            ]);
        } catch (\MongoCursorException $e) {
            if (11000 == $e->getCode()) {
                throw new ExistentUniquenessSharedException();
            }

            throw $e;
        }
    }
}
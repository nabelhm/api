<?php

namespace Muchacuba\Mobile;

use Cubalider\Phone\NumberFixer as PhoneNumberFixer;
use Muchacuba\Mobile\Profile\ConnectToStorageInternalWorker;
use Muchacuba\Mobile\Profile\ExistentNumberSharedException;
use Muchacuba\Mobile\Profile\ExistentUniquenessSharedException;
use Muchacuba\Mobile\Profile\InvalidNumberSharedException;
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
     *     "connectToStorageInternalWorker" = @Di\Inject("muchacuba.mobile.profile.connect_to_storage_internal_worker")
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
     * @param string $number
     *
     * @throws InvalidNumberSharedException
     * @throws ExistentNumberSharedException
     * @throws ExistentUniquenessSharedException
     * @throws \MongoCursorException
     */
    public function create($uniqueness, $number)
    {
        try {
            $number = PhoneNumberFixer::fix($number);
        } catch (\InvalidArgumentException $e) {
            throw new InvalidNumberSharedException();
        }

        try {
            $this->connectToStorageInternalWorker->connect()->insert([
                'uniqueness' => $uniqueness,
                'number' => $number
            ]);
        } catch (\MongoCursorException $e) {
            if (11000 == $e->getCode()) {
                if (strpos($e->getMessage(), '$number_1') !== false) {
                    throw new ExistentNumberSharedException();
                }

                throw new ExistentUniquenessSharedException();
            }

            throw $e;
        }
    }
}
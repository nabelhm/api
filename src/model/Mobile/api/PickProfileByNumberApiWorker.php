<?php

namespace Muchacuba\Mobile;

use Cubalider\Phone\NumberFixer as PhoneNumberFixer;
use Muchacuba\Mobile\Profile\ConnectToStorageInternalWorker;
use Muchacuba\Mobile\Profile\NonExistentNumberApiException;
use JMS\DiExtraBundle\Annotation as Di;

/**
 * @author Yosmany Garcia <yosmanyga@gmail.com>
 *
 * @Di\Service()
 */
class PickProfileByNumberApiWorker
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
    function __construct(ConnectToStorageInternalWorker $connectToStorageInternalWorker)
    {
        $this->connectToStorageInternalWorker = $connectToStorageInternalWorker;
    }

    /**
     * Picks the profile with given number.
     *
     * @param string $number
     *
     * @return array
     *
     * @throws NonExistentNumberApiException
     */
    public function pick($number)
    {
        $number = PhoneNumberFixer::fix($number);

        $profile = $this->connectToStorageInternalWorker->connect()->findOne(
            [
                'number' => $number
            ],
            [
                '_id' => 0 // Exclude
            ]
        );

        if (!$profile) {
            throw new NonExistentNumberApiException();
        }

        return $profile;
    }
}

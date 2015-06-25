<?php

namespace Muchacuba\InfoSms\Profile;

use JMS\DiExtraBundle\Annotation as Di;

/**
 * @author Nabel Hernandez <nabelhm@cubalider.com>
 * @author Yosmany Garcia <yosmanyga@gmail.com>
 *
 * @Di\Service()
 */
class DecreaseBalanceInternalWorker
{
    /**
     * @var ConnectToStorageInternalWorker
     */
    private $connectToStorageInternalWorker;

    /**
     * @param ConnectToStorageInternalWorker $connectToStorageInternalWorker
     *
     * @Di\InjectParams({
     *     "connectToStorageInternalWorker" = @Di\Inject("muchacuba.info_sms.profile.connect_to_storage_internal_worker"),
     * })
     */
    function __construct(
        ConnectToStorageInternalWorker $connectToStorageInternalWorker
    )
    {
        $this->connectToStorageInternalWorker = $connectToStorageInternalWorker;
    }

    /**
     * Decreases to the profile with given uniqueness, the given amount of
     * messages.
     *
     * @param string $uniqueness
     * @param int    $amount
     *
     * @throws NonExistentUniquenessInternalException
     * @throws InsufficientBalanceInternalException
     */
    public function decrease($uniqueness, $amount)
    {
        $item = $this->connectToStorageInternalWorker->connect()
            ->findOne([
                'uniqueness' => $uniqueness
            ]);

        if (!$item) {
            throw new NonExistentUniquenessInternalException($uniqueness);
        }

        if ($amount > $item['balance']) {
            throw new InsufficientBalanceInternalException();
        }

        $this->connectToStorageInternalWorker->connect()->update(
            [
                'uniqueness' => $uniqueness
            ],
            ['$inc' => [
                'balance' => -1 * $amount,
            ]]
        );
    }
}

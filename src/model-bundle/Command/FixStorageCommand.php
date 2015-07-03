<?php

namespace Muchacuba\ModelBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Muchacuba\RechargeCard\AssignedCard\ConnectToStorageInternalWorker as ConnectToRechargeCardAssignedCardStorageInternalWorker;
use Muchacuba\RechargeCard\Profile\ConnectToStorageInternalWorker as ConnectToProfileStorageInternalWorker;

/**
 * @author Yosmany Garcia <yosmanyga@gmail.com>
 */
class FixStorageCommand extends ContainerAwareCommand
{
    /**
     * {@inheritDoc}
     */
    protected function configure()
    {
        $this
            ->setName('muchacuba:fix-storage')
            ->setDescription('Fix storage');
    }

    /**
     * {@inheritDoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        /** @var ConnectToRechargeCardAssignedCardStorageInternalWorker $connectToRechargeCardAssignedCardStorageInternalWorker */
        $connectToRechargeCardAssignedCardStorageInternalWorker = $this->getContainer()->get('muchacuba.recharge_card.assigned_card.connect_to_storage_internal_worker');

        /** @var ConnectToProfileStorageInternalWorker $connectToProfileStorageInternalWorker */
        $connectToProfileStorageInternalWorker = $this->getContainer()->get('muchacuba.recharge_card.profile.connect_to_storage_internal_worker');

        $assignedCards = $connectToRechargeCardAssignedCardStorageInternalWorker->connect()->find();
        $assignedCardsByUniqueness = [];
        foreach ($assignedCards as $assignedCard) {
            if (!isset($assignedCardsByUniqueness[$assignedCard['uniqueness']])) {
                $assignedCardsByUniqueness[$assignedCard['uniqueness']] = [];
            }

            $assignedCardsByUniqueness[$assignedCard['uniqueness']][] = $assignedCard['card'];
        }

        foreach ($assignedCardsByUniqueness as $uniqueness => $cards) {
            $profile = $connectToProfileStorageInternalWorker->connect()->findOne([
                'uniqueness' => $uniqueness
            ]);

            $connectToProfileStorageInternalWorker->connect()->update(
                [
                    'uniqueness' => $uniqueness
                ],
                [
                    'uniqueness' => $uniqueness,
                    'debt' => $profile['debt'],
                    'cards' => $cards
                ]
            );
        }

        $connectToRechargeCardAssignedCardStorageInternalWorker->connect()->drop();
    }
}

<?php

namespace Muchacuba\ModelBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
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
        $client = new \MongoClient($this->getContainer()->getParameter('mongo_server'));
        $db = $client->selectDB($this->getContainer()->getParameter('mongo_db'));
        $assignedCardsCollection = $db->selectCollection('mc_recharge_card_assigned_cards');

        /** @var ConnectToProfileStorageInternalWorker $connectToProfileStorageInternalWorker */
        $connectToProfileStorageInternalWorker = $this->getContainer()->get('muchacuba.recharge_card.profile.connect_to_storage_internal_worker');

        $profiles = $connectToProfileStorageInternalWorker->connect()->find();
        foreach ($profiles as $profile) {
            $assignedCards = $assignedCardsCollection
                ->find([
                    'uniqueness' => $profile['uniqueness']
                ]);
            $cards = [];
            foreach ($assignedCards as $card) {
                $cards[] = $card['card'];
            }

            $connectToProfileStorageInternalWorker->connect()->update(
                [
                    'uniqueness' => $profile['uniqueness']
                ],
                [
                    'uniqueness' => $profile['uniqueness'],
                    'debt' => $profile['debt'],
                    'cards' => $cards
                ]
            );
        }

        $assignedCardsCollection->drop();
    }
}

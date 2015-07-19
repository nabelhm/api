<?php

namespace Muchacuba\ModelBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Muchacuba\InfoSms\Message\Stat\ConnectToStorageInternalWorker as ConnectToMessageStatStorageInternalWorker;

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
        /** @var ConnectToMessageStatStorageInternalWorker $connectToMessageStatStorageInternalWorker */
        $connectToMessageStatStorageInternalWorker = $this->getContainer()->get('muchacuba.info_sms.message.stat.connect_to_storage_internal_worker');

        $stats = $connectToMessageStatStorageInternalWorker->connect()->find();
        foreach ($stats as $stat) {
            $connectToMessageStatStorageInternalWorker->connect()->update(
                [
                    '_id' => $stat['_id']
                ],
                [
                    'id' => $stat['id'],
                    'body' => $stat['body'],
                    'topics' => $stat['topics'],
                    'total' => $stat['total'],
                    'delivered' => $stat['delivered'],
                    'notDelivered' => $stat['notDelivered'],
                    'timestamp' => new \MongoDate(strtotime(sprintf(
                        "%s-%s-%s %s",
                        $stat['year'],
                        $stat['month'],
                        $stat['day'],
                        $stat['time']
                    )))
                ]
            );
        }
    }
}

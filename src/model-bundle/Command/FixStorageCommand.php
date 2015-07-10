<?php

namespace Muchacuba\ModelBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Muchacuba\InfoSms\Subscription\Operation\ConnectToStorageInternalWorker as ConnectToSubscriptionOperationStorageInternalWorker;

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
        /** @var ConnectToSubscriptionOperationStorageInternalWorker $connectToSubscriptionOperationStorageInternalWorker */
        $connectToSubscriptionOperationStorageInternalWorker = $this->getContainer()->get('muchacuba.info_sms.subscription.operation.connect_to_storage_internal_worker');

        $operations = $connectToSubscriptionOperationStorageInternalWorker->connect()->find();
        foreach ($operations as $operation) {
            $connectToSubscriptionOperationStorageInternalWorker->connect()->update(
                [
                    '_id' => $operation['_id']
                ],
                [
                    'mobile' => $operation['mobile'],
                    'uniqueness' => $operation['uniqueness'],
                    'topics' => $operation['topics'],
                    'type' => $operation['type'],
                    'timestamp' => new \MongoDate(strtotime(sprintf(
                        "%s-%s-%s",
                        $operation['year'],
                        $operation['month'],
                        $operation['day']
                    )))
                ]
            );
        }
    }
}

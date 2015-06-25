<?php

namespace Cubalider\SmsBundle\Command;

use Cubalider\Sms\ProcessDeliveryOperationsApiWorker;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * @author Yosmany Garcia <yosmanyga@gmail.com>
 */
class ProcessDeliveryOperationsCommand extends ContainerAwareCommand
{
    /**
     * {@inheritDoc}
     */
    protected function configure()
    {
        $this
            ->setName('cubalider:sms:process-delivery-operations')
            ->setDescription('Process delivery operations');
    }

    /**
     * {@inheritDoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        /** @var ProcessDeliveryOperationsApiWorker $processDeliveryOperationsApiWorker */
        $processDeliveryOperationsApiWorker = $this->getContainer()->get('cubalider.sms.process_delivery_operations_api_worker');

        $processDeliveryOperationsApiWorker->process();
    }
}

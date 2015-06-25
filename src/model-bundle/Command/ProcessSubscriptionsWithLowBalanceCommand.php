<?php

namespace Muchacuba\ModelBundle\Command;

use Muchacuba\InfoSms\ProcessSubscriptionsWithLowBalanceApiWorker;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * @author Yosmany Garcia <yosmanyga@gmail.com>
 */
class ProcessSubscriptionsWithLowBalanceCommand extends ContainerAwareCommand
{
    /**
     * {@inheritDoc}
     */
    protected function configure()
    {
        $this
            ->setName('muchacuba:info-sms:process-subscriptions-with-low-balance')
            ->setDescription('Process subscriptions with low balance');
    }

    /**
     * {@inheritDoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        /** @var ProcessSubscriptionsWithLowBalanceApiWorker $processSubscriptionsWithLowBalanceApiWorker */
        $processSubscriptionsWithLowBalanceApiWorker = $this->getContainer()->get('muchacuba.info_sms.process_subscriptions_with_low_balance_api_worker');

        $processSubscriptionsWithLowBalanceApiWorker->process();
    }
}

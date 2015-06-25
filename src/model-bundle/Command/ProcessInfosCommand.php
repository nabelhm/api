<?php

namespace Muchacuba\ModelBundle\Command;

use Muchacuba\InfoSms\ProcessInfosApiWorker;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * @author Yosmany Garcia <yosmanyga@gmail.com>
 */
class ProcessInfosCommand extends ContainerAwareCommand
{
    /**
     * {@inheritDoc}
     */
    protected function configure()
    {
        $this
            ->setName('muchacuba:info-sms:process-infos')
            ->setDescription('Process infos');
    }

    /**
     * {@inheritDoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        /** @var ProcessInfosApiWorker $processInfosApiWorker */
        $processInfosApiWorker = $this->getContainer()->get('muchacuba.info_sms.process_infos_api_worker');

        $processInfosApiWorker->process();
    }
}

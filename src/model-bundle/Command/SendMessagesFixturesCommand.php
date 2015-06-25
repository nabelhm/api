<?php

namespace Muchacuba\ModelBundle\Command;

use Cubalider\Sms\SendMessagesTestWorker;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * @author Yosmany Garcia <yosmanyga@gmail.com>
 */
class SendMessagesFixturesCommand extends ContainerAwareCommand
{
    /**
     * {@inheritDoc}
     */
    protected function configure()
    {
        $this
            ->setName('muchacuba:fixtures:send-messages')
            ->setDescription('Send messages fixtures');
    }

    /**
     * {@inheritDoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        /** @var SendMessagesTestWorker $sendMessagesTestWorker */
        $sendMessagesTestWorker = $this->getContainer()
            ->get('cubalider.sms.send_messages_test_worker');

        $sendMessagesTestWorker->send(4);
    }
}

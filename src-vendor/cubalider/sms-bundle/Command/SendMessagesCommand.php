<?php

namespace Cubalider\SmsBundle\Command;

use Cubalider\Sms\SendMessagesApiWorker;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * @author Yosmany Garcia <yosmanyga@gmail.com>
 */
class SendMessagesCommand extends ContainerAwareCommand
{
    /**
     * {@inheritDoc}
     */
    protected function configure()
    {
        $this
            ->setName('cubalider:sms:send-messages')
            ->setDescription('Send messages');
    }

    /**
     * {@inheritDoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        /** @var SendMessagesApiWorker $sendMessagesApiWorker */
        $sendMessagesApiWorker = $this->getContainer()->get('cubalider.sms.send_messages_api_worker');

        $sendMessagesApiWorker->send();
    }
}

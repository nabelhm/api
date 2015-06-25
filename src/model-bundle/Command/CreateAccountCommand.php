<?php

namespace Muchacuba\ModelBundle\Command;

use Muchacuba\User\CreateAccountTestWorker;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * @author Yosmany Garcia <yosmanyga@gmail.com>
 */
class CreateAccountCommand extends ContainerAwareCommand
{
    /**
     * {@inheritDoc}
     */
    protected function configure()
    {
        $this
            ->setName('muchacuba:user:create-account')
            ->addArgument('username', InputArgument::REQUIRED, 'The mobile or email')
            ->addArgument('password', InputArgument::REQUIRED, 'The password')
            ->addArgument('roles', InputArgument::IS_ARRAY, 'The roles')
            ->setDescription('Creates an account')
            ->setHelp('i.e.:php app/console muchacuba:user:create-account yosmanyga@gmail.com thepass ROLE_ADMIN');
    }

    /**
     * {@inheritDoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        /** @var CreateAccountTestWorker $createAccountTestWorker */
        $createAccountTestWorker = $this->getContainer()
            ->get('muchacuba.user.create_account_test_worker');

        $createAccountTestWorker->create(
            uniqid(),
            $input->getArgument('username'),
            $input->getArgument('password'),
            $input->getArgument('roles')
        );
    }
}

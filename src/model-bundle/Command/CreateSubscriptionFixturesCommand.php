<?php

namespace Muchacuba\ModelBundle\Command;

use Muchacuba\Credit\Profile\IncreaseBalanceTestWorker;
use Muchacuba\InfoSms\CollectResellPackagesApiWorker;
use Muchacuba\InfoSms\CollectTopicsApiWorker;
use Muchacuba\InfoSms\CreateSubscriptionApiWorker;
use Muchacuba\User\CollectAccountsApiWorker;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * @author Yosmany Garcia <yosmanyga@gmail.com>
 */
class CreateSubscriptionFixturesCommand extends ContainerAwareCommand
{
    /**
     * {@inheritDoc}
     */
    protected function configure()
    {
        $this
            ->setName('muchacuba:fixtures:create-subscriptions')
            ->setDescription('Create subscription fixtures');
    }

    /**
     * {@inheritDoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        /** @var CollectTopicsApiWorker $collectTopicsApiWorker */
        $collectTopicsApiWorker = $this->getContainer()
            ->get('muchacuba.info_sms.collect_topics_api_worker');
        $topics = iterator_to_array($collectTopicsApiWorker->collect());

        /** @var CollectResellPackagesApiWorker $collectResellPackagesApiWorker */
        $collectResellPackagesApiWorker = $this->getContainer()
            ->get('muchacuba.info_sms.collect_resell_packages_api_worker');
        $resellPackages = iterator_to_array($collectResellPackagesApiWorker->collect());

        /** @var CreateSubscriptionApiWorker $createSubscriptionApiWorker */
        $createSubscriptionApiWorker = $this->getContainer()
            ->get('muchacuba.info_sms.create_subscription_api_worker');

        /** @var \Faker\Generator $generator */
        $generator = $this
            ->getContainer()
            ->get('faker.generator');

        /** @var CollectAccountsApiWorker $collectAccountsApiWorker */
        $collectAccountsApiWorker = $this->getContainer()
            ->get('muchacuba.user.collect_accounts_api_worker');

        $accounts = $collectAccountsApiWorker->collect();

        if (count($topics) > 0 && count($resellPackages) > 0) {
            $i = rand(0, count($accounts) - 1);
            while (in_array('ROLE_INFO_SMS_RESELLER', $accounts[$i]['roles'])) {
                $i = rand(0, count($accounts) - 1);
            }
            $uniqueness = $accounts[$i]['uniqueness'];

            /** @var IncreaseBalanceTestWorker $increaseBalanceTestWorker */
            $increaseBalanceTestWorker = $this->getContainer()
                ->get('muchacuba.info_sms.profile.increase_balance_test_worker');

            $increaseBalanceTestWorker->increase(
                $uniqueness,
                5000
            );

            for ($i = 0; $i <= rand(3, 6); $i++) {
                $subscriptionTopics = [];
                for ($j = 0; $j <= rand(1, 3); $j++) {
                    $topic = $topics[rand(0, count($topics) - 1)]['id'];
                    if (!in_array($topic, $subscriptionTopics)) {
                        $subscriptionTopics[] = $topic;
                    }
                }

                $createSubscriptionApiWorker->create(
                    sprintf("53%s", rand(111111, 999999)),
                    $uniqueness,
                    $generator->name,
                    $subscriptionTopics,
                    $resellPackages[rand(0, count($resellPackages) - 1)]['id']
                );
            }
        }
    }
}

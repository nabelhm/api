<?php

namespace Muchacuba;

use Behat\Behat\Context\SnippetAcceptingContext;
use Behat\Behat\Hook\Scope\AfterStepScope;
use Behat\Symfony2Extension\Context\KernelAwareContext;
use Cubalider\Sms\CollectMessagesTestWorker;
use Muchacuba\InfoSms\CollectInfosTestWorker;
use Muchacuba\InfoSms\CollectPackagesTestWorker;
use Muchacuba\InfoSms\CollectProfilesTestWorker as CollectInfoSmsProfilesTestWorker;
use Muchacuba\Credit\CollectProfilesTestWorker as CollectCreditProfilesTestWorker;
use Muchacuba\InfoSms\CollectResellPackagesTestWorker;
use Muchacuba\InfoSms\CollectSubscriptionsTestWorker;
use Muchacuba\InfoSms\CollectTopicsTestWorker;
use Muchacuba\InfoSms\Subscription\CollectOperationsTestWorker as CollectSubscriptionOperationsTestWorker;
use Muchacuba\InfoSms\Subscription\LowBalanceReminder\CollectLogsTestWorker as CollectSubscriptionLowBalanceReminderLogsTestWorker;
use Symfony\Component\HttpKernel\KernelInterface;
use PHPUnit_Framework_Assert as Assert;
use Muchacuba\InfoSms\Message\CollectLinksTestWorker as CollectMessageLinksTestWorker;
use Muchacuba\InfoSms\Message\CollectLatestStatsApiWorker as CollectLatestMessageStatsApiWorker;
use Muchacuba\Credit\Profile\Balance\CollectOperationsTestWorker as CollectCreditProfileBalanceOperationsTestWorker;

/**
 * @author Yosmany Garcia <yosmanyga@gmail.com>
 */
class Context implements SnippetAcceptingContext, KernelAwareContext
{
    /**
     * @var \Symfony\Component\HttpKernel\KernelInterface
     */
    private $kernel;

    /**
     * @var string[]
     */
    private $states;

    /**
     *
     */
    function __construct()
    {
        $this->states = [
            'Cubalider\Sms\Message',
            'Muchacuba\Credit\Profile',
            'Muchacuba\Credit\Profile\Balance\Operation',
            'Muchacuba\InfoSms\Info',
            'Muchacuba\InfoSms\Message\Link',
            'Muchacuba\InfoSms\Message\Stat',
            'Muchacuba\InfoSms\Package',
            'Muchacuba\InfoSms\Profile',
            'Muchacuba\InfoSms\ResellPackage',
            'Muchacuba\InfoSms\Subscription',
            'Muchacuba\InfoSms\Subscription\LowBalanceReminder\Log',
            'Muchacuba\InfoSms\Subscription\Operation',
            'Muchacuba\InfoSms\Topic',
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function setKernel(KernelInterface $kernel)
    {
        $this->kernel = $kernel;
    }

    /**
     * @BeforeScenario
     */
    public function dropStorage()
    {
        $client = new \MongoClient(
            $this->kernel->getContainer()->getParameter('mongo_server')
        );

        $client->dropDB(
            $this->kernel->getContainer()->getParameter('mongo_db')
        );
    }

    /**
     * @AfterStep
     *
     * @param AfterStepScope $scope
     */
    public function saveState(AfterStepScope $scope)
    {
        $backgroundSteps = $scope->getFeature()->getBackground()->getSteps();

        // If it's not the last step in background
        if ($scope->getStep() !== $backgroundSteps[count($backgroundSteps) - 1]) {
            return;
        }

        /** @var CollectMessagesTestWorker $collectWorker */
        $collectWorker = $this->kernel->getContainer()->get('cubalider.sms.collect_messages_test_worker');
        $this->states['Cubalider\Sms\Message'] = iterator_to_array($collectWorker->collect());

        /** @var CollectCreditProfilesTestWorker $collectWorker */
        $collectWorker = $this->kernel->getContainer()->get('muchacuba.credit.collect_profiles_test_worker');
        $this->states['Muchacuba\Credit\Profile'] = iterator_to_array($collectWorker->collect());

        /** @var CollectCreditProfileBalanceOperationsTestWorker $collectWorker */
        $collectWorker = $this->kernel->getContainer()->get('muchacuba.credit.profile.balance.collect_operations_test_worker');
        $this->states['Muchacuba\Credit\Profile\Balance\Operation'] = iterator_to_array($collectWorker->collect());

        /** @var CollectInfosTestWorker $collectWorker */
        $collectWorker = $this->kernel->getContainer()->get('muchacuba.info_sms.collect_infos_test_worker');
        $this->states['Muchacuba\InfoSms\Info'] = iterator_to_array($collectWorker->collect());

        /** @var CollectMessageLinksTestWorker $collectWorker */
        $collectWorker = $this->kernel->getContainer()->get('muchacuba.info_sms.message.collect_links_test_worker');
        $this->states['Muchacuba\InfoSms\Message\Link'] = iterator_to_array($collectWorker->collect());

        /** @var CollectLatestMessageStatsApiWorker $collectWorker */
        $collectWorker = $this->kernel->getContainer()->get('muchacuba.info_sms.message.collect_latest_stats_api_worker');
        $this->states['Muchacuba\InfoSms\Message\Stat'] = iterator_to_array($collectWorker->collect());

        /** @var CollectPackagesTestWorker $collectWorker */
        $collectWorker = $this->kernel->getContainer()->get('muchacuba.info_sms.collect_packages_test_worker');
        $this->states['Muchacuba\InfoSms\Package'] = iterator_to_array($collectWorker->collect());

        /** @var CollectInfoSmsProfilesTestWorker $collectWorker */
        $collectWorker = $this->kernel->getContainer()->get('muchacuba.info_sms.collect_profiles_test_worker');
        $this->states['Muchacuba\InfoSms\Profile'] = iterator_to_array($collectWorker->collect());

        /** @var CollectResellPackagesTestWorker $collectWorker */
        $collectWorker = $this->kernel->getContainer()->get('muchacuba.info_sms.collect_resell_packages_test_worker');
        $this->states['Muchacuba\InfoSms\ResellPackage'] = iterator_to_array($collectWorker->collect());

        /** @var CollectSubscriptionsTestWorker $collectWorker */
        $collectWorker = $this->kernel->getContainer()->get('muchacuba.info_sms.collect_subscriptions_test_worker');
        $this->states['Muchacuba\InfoSms\Subscription'] = iterator_to_array($collectWorker->collect());

        /** @var CollectSubscriptionLowBalanceReminderLogsTestWorker $collectWorker */
        $collectWorker = $this->kernel->getContainer()->get('muchacuba.info_sms.subscription.low_balance_reminder.collect_logs_test_worker');
        $this->states['Muchacuba\InfoSms\Subscription\LowBalanceReminder\Log'] = iterator_to_array($collectWorker->collect());

        /** @var CollectSubscriptionOperationsTestWorker $collectWorker */
        $collectWorker = $this->kernel->getContainer()->get('muchacuba.info_sms.subscription.collect_operations_test_worker');
        $this->states['Muchacuba\InfoSms\Subscription\Operation'] = iterator_to_array($collectWorker->collect());

        /** @var CollectTopicsTestWorker $collectWorker */
        $collectWorker = $this->kernel->getContainer()->get('muchacuba.info_sms.collect_topics_test_worker');
        $this->states['Muchacuba\InfoSms\Topic'] = iterator_to_array($collectWorker->collect());
    }

    /**
     * @AfterScenario
     */
    public function theSystemShouldNotHaveAnyOtherChange()
    {
        if (isset($this->states['Cubalider\Sms\Message'])) {
            /** @var CollectInfosTestWorker $collectWorker */
            $collectWorker = $this->kernel->getContainer()->get('cubalider.sms.collect_messages_test_worker');

            try {
                Assert::assertEquals(
                    $this->states['Cubalider\Sms\Message'],
                    iterator_to_array($collectWorker->collect())
                );
            } catch (\PHPUnit_Framework_ExpectationFailedException $e) {
                $this->throwException($e, 'Cubalider\Sms\Message');
            }
        }

        if (isset($this->states['Muchacuba\Credit\Profile'])) {
            /** @var CollectCreditProfilesTestWorker $collectWorker */
            $collectWorker = $this->kernel->getContainer()->get('muchacuba.credit.collect_profiles_test_worker');

            try {
                Assert::assertEquals(
                    $this->states['Muchacuba\Credit\Profile'],
                    iterator_to_array($collectWorker->collect())
                );
            } catch (\PHPUnit_Framework_ExpectationFailedException $e) {
                $this->throwException($e, 'Muchacuba\Credit\Profile');
            }
        }

        if (isset($this->states['Muchacuba\Credit\Profile\Balance\Operation'])) {
            /** @var CollectCreditProfilesTestWorker $collectWorker */
            $collectWorker = $this->kernel->getContainer()->get('muchacuba.credit.profile.balance.collect_operations_test_worker');

            try {
                Assert::assertEquals(
                    $this->states['Muchacuba\Credit\Profile\Balance\Operation'],
                    iterator_to_array($collectWorker->collect())
                );
            } catch (\PHPUnit_Framework_ExpectationFailedException $e) {
                $this->throwException($e, 'Muchacuba\Credit\Profile\Balance\Operation');
            }
        }

        if (isset($this->states['Muchacuba\InfoSms\Info'])) {
            /** @var CollectInfosTestWorker $collectWorker */
            $collectWorker = $this->kernel->getContainer()->get('muchacuba.info_sms.collect_infos_test_worker');

            try {
                Assert::assertEquals(
                    $this->states['Muchacuba\InfoSms\Info'],
                    iterator_to_array($collectWorker->collect())
                );
            } catch (\PHPUnit_Framework_ExpectationFailedException $e) {
                $this->throwException($e, 'Muchacuba\InfoSms\Info');
            }
        }

        if (isset($this->states['Muchacuba\InfoSms\Message\Link'])) {
            /** @var CollectMessageLinksTestWorker $collectWorker */
            $collectWorker = $this->kernel->getContainer()->get('muchacuba.info_sms.message.collect_links_test_worker');

            try {
                Assert::assertEquals(
                    $this->states['Muchacuba\InfoSms\Message\Link'],
                    iterator_to_array($collectWorker->collect())
                );
            } catch (\PHPUnit_Framework_ExpectationFailedException $e) {
                $this->throwException($e, 'Muchacuba\InfoSms\Message\Link');
            }
        }

        if (isset($this->states['Muchacuba\InfoSms\Message\Stat'])) {
            /** @var CollectLatestMessageStatsApiWorker $collectWorker */
            $collectWorker = $this->kernel->getContainer()->get('muchacuba.info_sms.message.collect_latest_stats_api_worker');

            try {
                Assert::assertEquals(
                    $this->states['Muchacuba\InfoSms\Message\Stat'],
                    iterator_to_array($collectWorker->collect())
                );
            } catch (\PHPUnit_Framework_ExpectationFailedException $e) {
                $this->throwException($e, 'Muchacuba\InfoSms\Message\Stat');
            }
        }

        if (isset($this->states['Muchacuba\InfoSms\Package'])) {
            /** @var CollectPackagesTestWorker $collectWorker */
            $collectWorker = $this->kernel->getContainer()->get('muchacuba.info_sms.collect_packages_test_worker');

            try {
                Assert::assertEquals(
                    $this->states['Muchacuba\InfoSms\Package'],
                    iterator_to_array($collectWorker->collect())
                );
            } catch (\PHPUnit_Framework_ExpectationFailedException $e) {
                $this->throwException($e, 'Muchacuba\InfoSms\Package');
            }
        }

        if (isset($this->states['Muchacuba\InfoSms\Profile'])) {
            /** @var CollectInfoSmsProfilesTestWorker $collectWorker */
            $collectWorker = $this->kernel->getContainer()->get('muchacuba.info_sms.collect_profiles_test_worker');

            try {
                Assert::assertEquals(
                    $this->states['Muchacuba\InfoSms\Profile'],
                    iterator_to_array($collectWorker->collect())
                );
            } catch (\PHPUnit_Framework_ExpectationFailedException $e) {
                $this->throwException($e, 'Muchacuba\InfoSms\Profile');
            }
        }

        if (isset($this->states['Muchacuba\InfoSms\ResellPackage'])) {
            /** @var CollectResellPackagesTestWorker $collectWorker */
            $collectWorker = $this->kernel->getContainer()->get('muchacuba.info_sms.collect_resell_packages_test_worker');

            try {
                Assert::assertEquals(
                    $this->states['Muchacuba\InfoSms\ResellPackage'],
                    iterator_to_array($collectWorker->collect())
                );
            } catch (\PHPUnit_Framework_ExpectationFailedException $e) {
                $this->throwException($e, 'Muchacuba\InfoSms\ResellPackage');
            }
        }

        if (isset($this->states['Muchacuba\InfoSms\Subscription'])) {
            /** @var CollectSubscriptionsTestWorker $collectWorker */
            $collectWorker = $this->kernel->getContainer()->get('muchacuba.info_sms.collect_subscriptions_test_worker');

            try {
                Assert::assertEquals(
                    $this->states['Muchacuba\InfoSms\Subscription'],
                    iterator_to_array($collectWorker->collect())
                );
            } catch (\PHPUnit_Framework_ExpectationFailedException $e) {
                $this->throwException($e, 'Muchacuba\InfoSms\Subscription');
            }
        }

        if (isset($this->states['Muchacuba\InfoSms\Subscription\LowBalanceReminder\Log'])) {
            /** @var CollectSubscriptionLowBalanceReminderLogsTestWorker $collectWorker */
            $collectWorker = $this->kernel->getContainer()->get('muchacuba.info_sms.subscription.low_balance_reminder.collect_logs_test_worker');

            try {
                Assert::assertEquals(
                    $this->states['Muchacuba\InfoSms\Subscription\LowBalanceReminder\Log'],
                    iterator_to_array($collectWorker->collect())
                );
            } catch (\PHPUnit_Framework_ExpectationFailedException $e) {
                $this->throwException($e, 'Muchacuba\InfoSms\Subscription\LowBalanceReminder\Log');
            }
        }

        if (isset($this->states['Muchacuba\InfoSms\Subscription\Operation'])) {
            /** @var CollectSubscriptionOperationsTestWorker $collectWorker */
            $collectWorker = $this->kernel->getContainer()->get('muchacuba.info_sms.subscription.collect_operations_test_worker');

            try {
                Assert::assertEquals(
                    $this->states['Muchacuba\InfoSms\Subscription\Operation'],
                    iterator_to_array($collectWorker->collect())
                );
            } catch (\PHPUnit_Framework_ExpectationFailedException $e) {
                $this->throwException($e, 'Muchacuba\InfoSms\Subscription\Operation');
            }
        }

        if (isset($this->states['Muchacuba\InfoSms\Topic'])) {
            /** @var CollectTopicsTestWorker $collectWorker */
            $collectWorker = $this->kernel->getContainer()->get('muchacuba.info_sms.collect_topics_test_worker');

            try {
                Assert::assertEquals(
                    $this->states['Muchacuba\InfoSms\Topic'],
                    iterator_to_array($collectWorker->collect())
                );
            } catch (\PHPUnit_Framework_ExpectationFailedException $e) {
                $this->throwException($e, 'Muchacuba\InfoSms\Topic');
            }
        }
    }

    /**
     * @param string $index
     *
     * @return string[]
     */
    public function ignoreState($index)
    {
        unset($this->states[$index]);
    }

    /**
     * @param \Exception $e
     * @param $state
     *
     * @throws \Exception
     */
    private function throwException(\Exception $e, $state)
    {
        throw new \Exception(
            sprintf(
                "Invalid state on %s:\r\n%s",
                $state,
                $e->getMessage()
            )
        );
    }
}

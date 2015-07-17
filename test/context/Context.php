<?php

namespace Muchacuba;

use Behat\Behat\Context\SnippetAcceptingContext;
use Behat\Behat\Hook\Scope\AfterStepScope;
use Behat\Symfony2Extension\Context\KernelAwareContext;
use Cubalider\Sms\CollectMessagesTestWorker;
use Cubalider\Unique\CollectUniquenessTestWorker;
use Muchacuba\Authentication\CollectProfilesTestWorker as CollectAuthenticationProfilesTestWorker;
use Muchacuba\InfoSms\CollectInfosTestWorker;
use Muchacuba\InfoSms\CollectPackagesTestWorker;
use Muchacuba\InfoSms\CollectProfilesTestWorker as CollectInfoSmsProfilesTestWorker;
use Muchacuba\Credit\CollectProfilesTestWorker as CollectCreditProfilesTestWorker;
use Muchacuba\InfoSms\CollectResellPackagesTestWorker;
use Muchacuba\InfoSms\CollectSubscriptionsTestWorker;
use Muchacuba\InfoSms\CollectTopicsTestWorker;
use Muchacuba\InfoSms\Subscription\CollectOperationsTestWorker as CollectSubscriptionOperationsTestWorker;
use Muchacuba\InfoSms\Subscription\LowBalanceReminder\CollectLogsTestWorker as CollectSubscriptionLowBalanceReminderLogsTestWorker;
use Muchacuba\Internet\CollectProfilesTestWorker as CollectInternetProfilesTestWorker;
use Muchacuba\Mobile\CollectProfilesTestWorker as  CollectMobileProfilesTestWorker;
use Muchacuba\Privilege\CollectAssignedRolesTestWorker;
use Muchacuba\RechargeCard\CollectCardsTestWorker;
use Muchacuba\RechargeCard\CollectCategoriesTestWorker;
use Muchacuba\RechargeCard\CollectProfilesTestWorker as CollectRechargeCardProfilesTestWorker;
use Muchacuba\RechargeCard\CollectProfilesTestWorker;
use Symfony\Component\HttpKernel\KernelInterface;
use PHPUnit_Framework_Assert as Assert;
use Muchacuba\InfoSms\Message\CollectLinksTestWorker as CollectMessageLinksTestWorker;
use Muchacuba\InfoSms\Message\CollectLatestStatsApiWorker as CollectLatestMessageStatsApiWorker;
use Muchacuba\Credit\Profile\Balance\CollectOperationsTestWorker as CollectCreditProfileBalanceOperationsTestWorker;

/**
 * @author Yosmany Garcia <yosmanyga@gmail.com>
 * @author Nabel Hernandez <nabelhm@cubalider.com>
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
            'Cubalider\Uniqueness',
            'Muchacuba\Authentication\Profile',
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
            'Muchacuba\Internet\Profile',
            'Muchacuba\Mobile\Profile',
            'Muchacuba\Privilege\AssignedRoles',
            'Muchacuba\RechargeCard\Package',
            'Muchacuba\RechargeCard\Category',
            'Muchacuba\RechargeCard\Card',
            'Muchacuba\RechargeCard\Profile',
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

        /** @var CollectUniquenessTestWorker $collectWorker */
        $collectWorker = $this->kernel->getContainer()->get('cubalider.unique.collect_uniqueness_test_worker');
        $this->states['Cubalider\Uniqueness'] = iterator_to_array($collectWorker->collect());

        /** @var CollectAuthenticationProfilesTestWorker $collectWorker */
        $collectWorker = $this->kernel->getContainer()->get('muchacuba.authentication.collect_profiles_test_worker');
        $this->states['Muchacuba\Authentication\Profile'] = iterator_to_array($collectWorker->collect());

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
        $this->states['Muchacuba\Internet\Profile'] = iterator_to_array($collectWorker->collect());
        $this->states['Muchacuba\Topics\Profile'] = iterator_to_array($collectWorker->collect());

        /** @var CollectInternetProfilesTestWorker $collectWorker */
        $collectWorker = $this->kernel->getContainer()->get('muchacuba.internet.collect_profiles_test_worker');
        $this->states['Muchacuba\Internet\Profile'] = iterator_to_array($collectWorker->collect());

        /** @var CollectMobileProfilesTestWorker $collectWorker */
        $collectWorker = $this->kernel->getContainer()->get('muchacuba.mobile.collect_profiles_test_worker');
        $this->states['Muchacuba\Mobile\Profile'] = iterator_to_array($collectWorker->collect());

        /** @var CollectAssignedRolesTestWorker $collectWorker */
        $collectWorker = $this->kernel->getContainer()->get('muchacuba.privilege.collect_assigned_roles_test_worker');
        $this->states['Muchacuba\Privilege\AssignedRoles'] = iterator_to_array($collectWorker->collect());

        /** @var CollectPackagesTestWorker $collectWorker */
        $collectWorker = $this->kernel->getContainer()->get('muchacuba.recharge_card.collect_packages_test_worker');
        $this->states['Muchacuba\RechargeCard\Package'] = iterator_to_array($collectWorker->collect());

        /** @var CollectCategoriesTestWorker $collectWorker */
        $collectWorker = $this->kernel->getContainer()->get('muchacuba.recharge_card.collect_categories_test_worker');
        $this->states['Muchacuba\RechargeCard\Category'] = iterator_to_array($collectWorker->collect());

        /** @var CollectRechargeCardProfilesTestWorker $collectWorker */
        $collectWorker = $this->kernel->getContainer()->get('muchacuba.recharge_card.collect_profiles_test_worker');
        $this->states['Muchacuba\RechargeCard\Profile'] = iterator_to_array($collectWorker->collect());

        /** @var CollectCardsTestWorker $collectWorker */
        $collectWorker = $this->kernel->getContainer()->get('muchacuba.recharge_card.collect_cards_test_worker');
        $this->states['Muchacuba\RechargeCard\Card'] = iterator_to_array($collectWorker->collect());

        /** @var CollectProfilesTestWorker $collectWorker */
        $collectWorker = $this->kernel->getContainer()->get('muchacuba.recharge_card.collect_profiles_test_worker');
        $this->states['Muchacuba\RechargeCard\Profile'] = iterator_to_array($collectWorker->collect());
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

        if (isset($this->states['Cubalider\Uniqueness'])) {
            /** @var CollectUniquenessTestWorker $collectWorker */
            $collectWorker = $this->kernel->getContainer()->get('cubalider.unique.collect_uniqueness_test_worker');

            try {
                Assert::assertEquals(
                    $this->states['Cubalider\Uniqueness'],
                    iterator_to_array($collectWorker->collect())
                );
            } catch (\PHPUnit_Framework_ExpectationFailedException $e) {
                $this->throwException($e, 'Cubalider\Uniqueness');
            }
        }

        if (isset($this->states['Muchacuba\Authentication\Profile'])) {
            /** @var CollectAuthenticationProfilesTestWorker $collectWorker */
            $collectWorker = $this->kernel->getContainer()->get('muchacuba.authentication.collect_profiles_test_worker');

            try {
                Assert::assertEquals(
                    $this->states['Muchacuba\Authentication\Profile'],
                    iterator_to_array($collectWorker->collect())
                );
            } catch (\PHPUnit_Framework_ExpectationFailedException $e) {
                $this->throwException($e, 'Muchacuba\Authentication\Profile');
            }
        }

        if (isset($this->states['Cubalider\Uniqueness'])) {
            /** @var CollectUniquenessTestWorker $collectWorker */
            $collectWorker = $this->kernel->getContainer()->get('cubalider.unique.collect_uniqueness_test_worker');

            try {
                Assert::assertEquals(
                    $this->states['Cubalider\Uniqueness'],
                    iterator_to_array($collectWorker->collect())
                );
            } catch (\PHPUnit_Framework_ExpectationFailedException $e) {
                $this->throwException($e, 'Cubalider\Uniqueness');
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

        if (isset($this->states['Muchacuba\Internet\Profile'])) {
            /** @var CollectInternetProfilesTestWorker $collectWorker */
            $collectWorker = $this->kernel->getContainer()->get('muchacuba.internet.collect_profiles_test_worker');

            try {
                Assert::assertEquals(
                    $this->states['Muchacuba\Internet\Profile'],
                    iterator_to_array($collectWorker->collect())
                );
            } catch (\PHPUnit_Framework_ExpectationFailedException $e) {
                $this->throwException($e, 'Muchacuba\Internet\Profile');
            }
        }

        if (isset($this->states['Muchacuba\Mobile\Profile'])) {
            /** @var CollectMobileProfilesTestWorker $collectWorker */
            $collectWorker = $this->kernel->getContainer()->get('muchacuba.mobile.collect_profiles_test_worker');

            try {
                Assert::assertEquals(
                    $this->states['Muchacuba\Mobile\Profile'],
                    iterator_to_array($collectWorker->collect())
                );
            } catch (\PHPUnit_Framework_ExpectationFailedException $e) {
                $this->throwException($e, 'Muchacuba\Mobile\Profile');
            }
        }

        if (isset($this->states['Muchacuba\Privilege\AssignedRoles'])) {
            /** @var CollectAssignedRolesTestWorker $collectWorker */
            $collectWorker = $this->kernel->getContainer()->get('muchacuba.privilege.collect_assigned_roles_test_worker');

            try {
                Assert::assertEquals(
                    $this->states['Muchacuba\Privilege\AssignedRoles'],
                    iterator_to_array($collectWorker->collect())
                );
            } catch (\PHPUnit_Framework_ExpectationFailedException $e) {
                $this->throwException($e, 'Muchacuba\Privilege\AssignedRoles');
            }
        }

        if (isset($this->states['Muchacuba\Internet\Profile'])) {
            /** @var CollectInternetProfilesTestWorker $collectWorker */
            $collectWorker = $this->kernel->getContainer()->get('muchacuba.internet.collect_profiles_test_worker');

            try {
                Assert::assertEquals(
                    $this->states['Muchacuba\Internet\Profile'],
                    iterator_to_array($collectWorker->collect())
                );
            } catch (\PHPUnit_Framework_ExpectationFailedException $e) {
                $this->throwException($e, 'Muchacuba\Internet\Profile');
            }
        }

        if (isset($this->states['Muchacuba\Mobile\Profile'])) {
            /** @var CollectMobileProfilesTestWorker $collectWorker */
            $collectWorker = $this->kernel->getContainer()->get('muchacuba.mobile.collect_profiles_test_worker');

            try {
                Assert::assertEquals(
                    $this->states['Muchacuba\Mobile\Profile'],
                    iterator_to_array($collectWorker->collect())
                );
            } catch (\PHPUnit_Framework_ExpectationFailedException $e) {
                $this->throwException($e, 'Muchacuba\Mobile\Profile');
            }
        }

        if (isset($this->states['Muchacuba\Privilege\AssignedRoles'])) {
            /** @var CollectAssignedRolesTestWorker $collectWorker */
            $collectWorker = $this->kernel->getContainer()->get('muchacuba.privilege.collect_assigned_roles_test_worker');

            try {
                Assert::assertEquals(
                    $this->states['Muchacuba\Privilege\AssignedRoles'],
                    iterator_to_array($collectWorker->collect())
                );
            } catch (\PHPUnit_Framework_ExpectationFailedException $e) {
                $this->throwException($e, 'Muchacuba\Privilege\AssignedRoles');
            }
        }

        if (isset($this->states['Muchacuba\RechargeCard\Package'])) {
            /** @var CollectPackagesTestWorker $collectWorker */
            $collectWorker = $this->kernel->getContainer()->get('muchacuba.recharge_card.collect_packages_test_worker');

            try {
                Assert::assertEquals(
                    $this->states['Muchacuba\RechargeCard\Package'],
                    iterator_to_array($collectWorker->collect())
                );
            } catch (\PHPUnit_Framework_ExpectationFailedException $e) {
                $this->throwException($e, 'Muchacuba\RechargeCard\Package');
            }
        }

        if (isset($this->states['Muchacuba\RechargeCard\Category'])) {
            /** @var CollectCategoriesTestWorker $collectWorker */
            $collectWorker = $this->kernel->getContainer()->get('muchacuba.recharge_card.collect_categories_test_worker');

            try {
                Assert::assertEquals(
                    $this->states['Muchacuba\RechargeCard\Category'],
                    iterator_to_array($collectWorker->collect())
                );
            } catch (\PHPUnit_Framework_ExpectationFailedException $e) {
                $this->throwException($e, 'Muchacuba\RechargeCard\Category');
            }
        }

        if (isset($this->states['Muchacuba\RechargeCard\Profile'])) {
            /** @var CollectCategoriesTestWorker $collectWorker */
            $collectWorker = $this->kernel->getContainer()->get('muchacuba.recharge_card.collect_profiles_test_worker');

            try {
                Assert::assertEquals(
                    $this->states['Muchacuba\RechargeCard\Profile'],
                    iterator_to_array($collectWorker->collect())
                );
            } catch (\PHPUnit_Framework_ExpectationFailedException $e) {
                $this->throwException($e, 'Muchacuba\RechargeCard\Profile');
            }
        }

        if (isset($this->states['Muchacuba\RechargeCard\Card'])) {
            /** @var CollectCardsTestWorker $collectWorker */
            $collectWorker = $this->kernel->getContainer()->get('muchacuba.recharge_card.collect_cards_test_worker');

            try {
                Assert::assertEquals(
                    $this->states['Muchacuba\RechargeCard\Card'],
                    iterator_to_array($collectWorker->collect())
                );
            } catch (\PHPUnit_Framework_ExpectationFailedException $e) {
                $this->throwException($e, 'Muchacuba\RechargeCard\Card');
            }
        }

        if (isset($this->states['Muchacuba\RechargeCard\Profile'])) {
            /** @var CollectProfilesTestWorker $collectWorker */
            $collectWorker = $this->kernel->getContainer()->get('muchacuba.recharge_card.collect_profiles_test_worker');

            try {
                Assert::assertEquals(
                    $this->states['Muchacuba\RechargeCard\Profile'],
                    iterator_to_array($collectWorker->collect())
                );
            } catch (\PHPUnit_Framework_ExpectationFailedException $e) {
                $this->throwException($e, 'Muchacuba\RechargeCard\Profile');
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

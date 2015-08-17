<?php


namespace Muchacuba\InfoSms;

use Behat\Behat\Context\Environment\InitializedContextEnvironment;
use Behat\Behat\Context\SnippetAcceptingContext;
use Behat\Behat\Hook\Scope\BeforeScenarioScope;
use Behat\Gherkin\Node\PyStringNode;
use Behat\Symfony2Extension\Context\KernelAwareContext;
use Coduo\PHPMatcher\Factory\SimpleFactory;
use Cubalider\Sms\ProcessDeliveryOperationsApiWorker;
use Cubalider\Sms\SendAndDeliverMessageTestWorker;
use Symfony\Component\HttpKernel\KernelInterface;
use PHPUnit_Framework_Assert as Assert;
use Muchacuba\Context as RootContext;

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
     * @var RootContext
     */
    private $rootContext;

    /**
     * {@inheritdoc}
     */
    public function setKernel(KernelInterface $kernel)
    {
        $this->kernel = $kernel;
    }

    /**
     * @BeforeScenario
     *
     * @param BeforeScenarioScope $scope
     */
    public function gatherRootContext(BeforeScenarioScope $scope)
    {
        /** @var InitializedContextEnvironment $environment */
        $environment = $scope->getEnvironment();

        $this->rootContext = $environment->getContext('Muchacuba\Context');
    }

    /**
     * @Given the system has the following info sms infos:
     *
     * @param PyStringNode $body
     */
    public function theSystemHasTheFollowingInfos(PyStringNode $body)
    {
        $items = (array) json_decode($body->getRaw(), true);

        /** @var CreateInfoTestWorker $createInfoTestWorker */
        $createInfoTestWorker = $this->kernel
            ->getContainer()
            ->get('muchacuba.info_sms.create_info_test_worker');

        foreach ($items as $item) {
            $createInfoTestWorker->create(
                $item['id'],
                $item['body'],
                $item['topics']
            );
        }

        $this->rootContext->ignoreState('Muchacuba\InfoSms\ResellPackage');
    }

    /**
     * @Then the system should have the following info sms infos:
     *
     * @param PyStringNode $body
     */
    public function theSystemShouldHaveTheFollowingInfos(PyStringNode $body)
    {
        /** @var CollectInfosApiWorker $collectInfosApiWorker */
        $collectInfosApiWorker = $this->kernel
            ->getContainer()
            ->get('muchacuba.info_sms.collect_infos_api_worker');

        Assert::assertTrue(
            (new SimpleFactory())->createMatcher()->match(
            iterator_to_array($collectInfosApiWorker->collect()),
            (array) json_decode($body->getRaw(), true)
            )
        );

        $this->rootContext->ignoreState('Muchacuba\InfoSms\Info');
    }

    /**
     * @Given the system has the following info sms packages:
     *
     * @param PyStringNode $body
     */
    public function theSystemHasTheFollowingPackages(PyStringNode $body)
    {
        $items = (array) json_decode($body->getRaw(), true);

        /** @var CreatePackageTestWorker $createPackageTestWorker */
        $createPackageTestWorker = $this->kernel
            ->getContainer()
            ->get('muchacuba.info_sms.create_package_test_worker');

        foreach ($items as $item) {
            $createPackageTestWorker->create(
                $item['id'],
                $item['name'],
                (int) $item['amount'],
                (int) $item['price']
            );
        }

        $this->rootContext->ignoreState('Muchacuba\InfoSms\Package');
    }

    /**
     * @Then the system should have the following info sms packages:
     *
     * @param PyStringNode $body
     */
    public function theSystemShouldHaveTheFollowingPackages(PyStringNode $body)
    {
        /** @var CollectPackagesTestWorker $collectPackagesTestWorker */
        $collectPackagesTestWorker = $this->kernel
            ->getContainer()
            ->get('muchacuba.info_sms.collect_packages_test_worker');

        Assert::assertTrue(
            (new SimpleFactory())->createMatcher()->match(
                iterator_to_array($collectPackagesTestWorker->collect()),
                (array) json_decode($body->getRaw(), true)
            )
        );

        $this->rootContext->ignoreState('Muchacuba\InfoSms\Package');
    }
    
    /**
     * @Given the system has the following resell packages:
     *
     * @param PyStringNode $body
     */
    public function theSystemHasTheFollowingResellPackages(PyStringNode $body)
    {
        $items = (array) json_decode($body->getRaw(), true);

        /** @var CreateResellPackageTestWorker $createResellPackageTestWorker */
        $createResellPackageTestWorker = $this->kernel
            ->getContainer()
            ->get('muchacuba.info_sms.create_resell_package_test_worker');

        foreach ($items as $item) {
            $createResellPackageTestWorker->create(
                $item['id'],
                (int) $item['amount'],
                (int) $item['price'],
                $item['description']
            );
        }
    }

    /**
     * @Then the system should have the following info sms resell packages:
     *
     * @param PyStringNode $body
     */
    public function theSystemShouldHaveTheFollowingResellPackages(PyStringNode $body)
    {
        /** @var CollectResellPackagesTestWorker $collectResellPackagesTestWorker */
        $collectResellPackagesTestWorker = $this->kernel
            ->getContainer()
            ->get('muchacuba.info_sms.collect_resell_packages_test_worker');

        Assert::assertTrue(
            (new SimpleFactory())->createMatcher()->match(
                iterator_to_array($collectResellPackagesTestWorker->collect()),
                (array) json_decode($body->getRaw(), true)
            )
        );

        $this->rootContext->ignoreState('Muchacuba\InfoSms\ResellPackage');
    }
    
    /**
     * @Given the system has the following info sms topics:
     *
     * @param PyStringNode $body
     */
    public function theSystemHasTheFollowingTopics(PyStringNode $body)
    {
        $items = (array) json_decode($body->getRaw(), true);

        /** @var CreateTopicTestWorker $createTopicTestWorker */
        $createTopicTestWorker = $this->kernel
            ->getContainer()
            ->get('muchacuba.info_sms.create_topic_test_worker');

        foreach ($items as $item) {
            $createTopicTestWorker->create(
                $item['id'],
                $item['title'],
                $item['description'],
                $item['average'],
                $item['order']
            );
        }
    }

    /**
     * @Then the system should have the following info sms topics:
     *
     * @param PyStringNode $body
     */
    public function theSystemShouldHaveTheFollowingTopics(PyStringNode $body)
    {
        /** @var CollectTopicsTestWorker $collectTopicsTestWorker */
        $collectTopicsTestWorker = $this->kernel
            ->getContainer()
            ->get('muchacuba.info_sms.collect_topics_test_worker');

        Assert::assertTrue(
            (new SimpleFactory())->createMatcher()->match(
                iterator_to_array($collectTopicsTestWorker->collect()),
                (array) json_decode($body->getRaw(), true)
            )
        );

        $this->rootContext->ignoreState('Muchacuba\InfoSms\Topic');
    }
    
    /**
     * @Then the system should have the following info sms profiles:
     *
     * @param PyStringNode $body
     */
    public function theSystemShouldHaveTheFollowingProfiles(PyStringNode $body)
    {
        /** @var CollectProfilesTestWorker $collectProfilesTestWorker */
        $collectProfilesTestWorker = $this->kernel
            ->getContainer()
            ->get('muchacuba.info_sms.collect_profiles_test_worker');

        Assert::assertTrue(
            (new SimpleFactory())->createMatcher()->match(
                iterator_to_array($collectProfilesTestWorker->collect()),
                (array) json_decode($body->getRaw(), true)
            )
        );

        $this->rootContext->ignoreState('Muchacuba\InfoSms\Profile');
    }

    /**
     * @Given the system has the following info sms subscriptions:
     *
     * @param PyStringNode $body
     */
    public function theSystemHasTheFollowingSubscriptions(PyStringNode $body)
    {
        $items = (array) json_decode($body->getRaw(), true);

        /** @var CreateSubscriptionTestWorker $createSubscriptionTestWorker*/
        $createSubscriptionTestWorker = $this->kernel
            ->getContainer()
            ->get('muchacuba.info_sms.create_subscription_test_worker');

        foreach ($items as $item) {
            $createSubscriptionTestWorker->create(
                $item['mobile'],
                $item['uniqueness'],
                $item['alias'],
                $item['topics'],
                $item['trial'],
                $item['balance'],
                $item['active']
            );
        }

        $this->rootContext->ignoreState('Muchacuba\InfoSms\Subscription');
    }

    /**
     * @Then the system should have the following info sms subscriptions:
     *
     * @param PyStringNode $body
     */
    public function theSystemShouldHaveTheFollowingSubscriptionsFor(PyStringNode $body)
    {
        /** @var CollectSubscriptionsTestWorker $collectSubscriptionsWorker */
        $collectSubscriptionsWorker = $this->kernel
            ->getContainer()
            ->get('muchacuba.info_sms.collect_subscriptions_test_worker');

        Assert::assertTrue(
            (new SimpleFactory())->createMatcher()->match(
                iterator_to_array($collectSubscriptionsWorker->collect()),
                (array) json_decode($body->getRaw(), true)
            )
        );

        $this->rootContext->ignoreState('Muchacuba\InfoSms\Subscription');
    }

    /**
     * @Given the system processes subscriptions with low balance
     */
    public function theSystemProcessesSubscriptionsWithLowBalance()
    {
        /** @var ProcessSubscriptionsWithLowBalanceApiWorker $processSubscriptionsWithLowBalanceApiWorker */
        $processSubscriptionsWithLowBalanceApiWorker = $this->kernel
            ->getContainer()
            ->get('muchacuba.info_sms.process_subscriptions_with_low_balance_api_worker');

        $processSubscriptionsWithLowBalanceApiWorker->process();
    }

    /**
     * @Given the system processes info sms infos
     */
    public function theSystemProcessesInfos()
    {
        /** @var ProcessInfosApiWorker $processInfosApiWorker*/
        $processInfosApiWorker = $this->kernel
            ->getContainer()
            ->get('muchacuba.info_sms.process_infos_api_worker');

        $processInfosApiWorker->process();
    }

    /**
     * @Given the system send and deliver a message successfully
     */
    public function theSystemSendAndDeliverAMessageSuccessfully()
    {
        /** @var SendAndDeliverMessageTestWorker $sendAndDeliverMessageTestWorker */
        $sendAndDeliverMessageTestWorker = $this->kernel
            ->getContainer()
            ->get('cubalider.sms.send_and_deliver_message_test_worker');

        $sendAndDeliverMessageTestWorker->sendAndDeliver();
    }

    /**
     * @Given the system send and deliver a message unsuccessfully
     */
    public function theSystemSendAndDeliverAMessagesUnsuccessfully()
    {
        /** @var SendAndDeliverMessageTestWorker $sendAndDeliverMessageTestWorker */
        $sendAndDeliverMessageTestWorker = $this->kernel
            ->getContainer()
            ->get('cubalider.sms.send_and_deliver_message_test_worker');

        $sendAndDeliverMessageTestWorker->sendAndDeliver(false);
    }

    /**
     * @Given the system processes delivery operations
     */
    public function theSystemProcessesDeliveryOperations()
    {
        /** @var ProcessDeliveryOperationsApiWorker $processDeliveryOperationsApiWorker */
        $processDeliveryOperationsApiWorker = $this->kernel
            ->getContainer()
            ->get('cubalider.sms.process_delivery_operations_api_worker');

        $processDeliveryOperationsApiWorker->process();
    }

    /**
     * @Given the topic :topic is disabled
     */
    public function theTopicIsDisabled($topic)
    {
        /** @var DisableTopicTestWorker $disableSubscriptionTestWorker */
        $disableSubscriptionTestWorker = $this->kernel
            ->getContainer()
            ->get('muchacuba.info_sms.disable_topic_test_worker');

        $disableSubscriptionTestWorker->disable($topic);
    }
}

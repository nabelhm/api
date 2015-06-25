<?php


namespace Muchacuba\InfoSms\Subscription\LowBalanceReminder;

use Behat\Behat\Context\Environment\InitializedContextEnvironment;
use Behat\Behat\Context\SnippetAcceptingContext;
use Behat\Behat\Hook\Scope\BeforeScenarioScope;
use Behat\Gherkin\Node\PyStringNode;
use Behat\Symfony2Extension\Context\KernelAwareContext;
use Coduo\PHPMatcher\Factory\SimpleFactory;
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
     * @Given the system has the following info sms subscription low balance reminder logs:
     *
     * @param PyStringNode $body
     */
    public function theSystemHasTheFollowingLogs(PyStringNode $body)
    {
        $items = (array) json_decode($body->getRaw(), true);

        /** @var CreateLogTestWorker $createLogTestWorker*/
        $createLogTestWorker = $this->kernel
            ->getContainer()
            ->get('muchacuba.info_sms.subscription.low_balance_reminder.create_log_test_worker');

        foreach ($items as $item) {
            $createLogTestWorker->create(
                $item['mobile']
            );
        }

        $this->rootContext->ignoreState('Muchacuba\InfoSms\Subscription\LowBalanceReminder\Log');
    }

    /**
     * @Then the system should have the following info sms subscription low balance reminder logs:
     *
     * @param PyStringNode $body
     */
    public function theSystemShouldHaveTheFollowingOperations(PyStringNode $body)
    {
        /** @var CollectLogsTestWorker $collectLogsTestWorker */
        $collectLogsTestWorker = $this->kernel
            ->getContainer()
            ->get('muchacuba.info_sms.subscription.low_balance_reminder.collect_logs_test_worker');

        Assert::assertTrue(
            (new SimpleFactory())->createMatcher()->match(
                iterator_to_array($collectLogsTestWorker->collect()),
                (array) json_decode($body->getRaw(), true)
            )
        );

        $this->rootContext->ignoreState('Muchacuba\InfoSms\Subscription\LowBalanceReminder\Log');
    }
}

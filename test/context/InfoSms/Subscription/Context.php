<?php


namespace Muchacuba\InfoSms\Subscription;

use Behat\Behat\Context\Environment\InitializedContextEnvironment;
use Behat\Behat\Context\SnippetAcceptingContext;
use Behat\Behat\Hook\Scope\BeforeScenarioScope;
use Behat\Gherkin\Node\PyStringNode;
use Behat\Symfony2Extension\Context\KernelAwareContext;
use Coduo\PHPMatcher\Factory\SimpleFactory;
use Symfony\Component\HttpKernel\KernelInterface;
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
     * @Given the system has the following info sms subscription trial operations:
     *
     * @param PyStringNode $body
     */
    public function theSystemHasTheFollowingTrialOperations(PyStringNode $body)
    {
        $items = (array) json_decode($body->getRaw(), true);

        /** @var LogOperationTestWorker $logOperationTestWorker*/
        $logOperationTestWorker = $this->kernel
            ->getContainer()
            ->get('muchacuba.info_sms.subscription.log_operation_test_worker');

        foreach ($items as $item) {
            $logOperationTestWorker->logTrial(
                $item['mobile'],
                $item['uniqueness'],
                $item['topics'],
                $item['timestamp']
            );
        }

        $this->rootContext->ignoreState('Muchacuba\InfoSms\Subscription\Operation');
    }

    /**
     * @Given the system has the following info sms subscription create operations:
     *
     * @param PyStringNode $body
     */
    public function theSystemHasTheFollowingCreateOperations(PyStringNode $body)
    {
        $items = (array) json_decode($body->getRaw(), true);

        /** @var LogOperationTestWorker $logOperationTestWorker*/
        $logOperationTestWorker = $this->kernel
            ->getContainer()
            ->get('muchacuba.info_sms.subscription.log_operation_test_worker');

        foreach ($items as $item) {
            $logOperationTestWorker->logCreate(
                $item['mobile'],
                $item['uniqueness'],
                $item['topics'],
                $item['amount'],
                $item['timestamp']
            );
        }

        $this->rootContext->ignoreState('Muchacuba\InfoSms\Subscription\Operation');
    }

    /**
     * @Then the system should have the following info sms subscription operations:
     *
     * @param PyStringNode $body
     */
    public function theSystemShouldHaveTheFollowingOperations(PyStringNode $body)
    {
        /** @var CollectOperationsTestWorker $collectOperationsTestWorker */
        $collectOperationsTestWorker = $this->kernel
            ->getContainer()
            ->get('muchacuba.info_sms.subscription.collect_operations_test_worker');

        $expected = (array) json_decode($body->getRaw(), true);
        $actual = iterator_to_array($collectOperationsTestWorker->collect());

        (new SimpleFactory())->createMatcher()->match($actual, $expected)
            ?: (new \PHPUnit_Framework_Constraint_IsEqual($expected))->evaluate($actual);

        $this->rootContext->ignoreState('Muchacuba\InfoSms\Subscription\Operation');
    }
}

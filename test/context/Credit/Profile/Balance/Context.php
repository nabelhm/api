<?php

namespace Muchacuba\Credit\Profile\Balance;

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
 * @author Nabel Hernandez <nabelhm@cubalider.com>
 * @author Yosmany Garcia <yosmanyga@gmail.com>
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
     * {@inheritdoc}
     */
    public function setKernel(KernelInterface $kernel)
    {
        $this->kernel = $kernel;
    }

    /**
     * @Given the system has the following credit profile balance operations:
     *
     * @param PyStringNode $body
     */
    public function theSystemHasTheFollowingOperations(PyStringNode $body)
    {
        $items = (array) json_decode($body->getRaw(), true);

        /** @var LogOperationTestWorker $logOperationTestWorker */
        $logOperationTestWorker = $this->kernel
            ->getContainer()
            ->get('muchacuba.credit.profile.balance.log_operation_test_worker');

        foreach ($items as $item) {
            $logOperationTestWorker->log(
                $item['uniqueness'],
                $item['amount'],
                $item['impact'],
                $item['description']
            );

            sleep(1);
        }
    }

    /**
     * @Then the system should have the following credit profile balance operations:
     *
     * @param PyStringNode $body
     */
    public function theSystemShouldHaveTheFollowingOperations(PyStringNode $body)
    {
        /** @var CollectOperationsTestWorker $collectOperationsTestWorker */
        $collectOperationsTestWorker = $this->kernel
            ->getContainer()
            ->get('muchacuba.credit.profile.balance.collect_operations_test_worker');

        Assert::assertTrue(
            (new SimpleFactory())->createMatcher()->match(
                iterator_to_array($collectOperationsTestWorker->collect()),
                (array) json_decode($body->getRaw(), true)
            )
        );

        $this->rootContext->ignoreState('Muchacuba\Credit\Profile\Balance\Operation');
    }
}

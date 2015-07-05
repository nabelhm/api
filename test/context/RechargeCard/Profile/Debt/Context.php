<?php

namespace Muchacuba\Rechargecard\Profile\Debt;

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
     * @When the system has the following recharge card profile debt operations:
     *
     * @param PyStringNode $body
     */
    public function theSystemHasTheFollowingOperations(PyStringNode $body)
    {
        $items = (array) json_decode($body->getRaw(), true);

        /** @var LogOperationTestWorker $logOperationTestWorker */
        $logOperationTestWorker = $this->kernel
            ->getContainer()
            ->get('muchacuba.recharge_card.profile.debt.log_operation_test_worker');

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
     * @Then the system should have the following recharge card profile debt operations for :uniqueness:
     *
     * @param string       $uniqueness
     * @param PyStringNode $body
     */
    public function theSystemShouldHaveTheFollowingOperations($uniqueness, PyStringNode $body)
    {
        $expectedOperations = (array) json_decode($body->getRaw(), true);

        /** @var CollectOperationsApiWorker $collectOperationsApiWorker */
        $collectOperationsApiWorker = $this->kernel
            ->getContainer()
            ->get('muchacuba.recharge_card.profile.debt.collect_operations_api_worker');

        $actualOperations = iterator_to_array($collectOperationsApiWorker->collect($uniqueness));

        Assert::assertTrue(
            (new SimpleFactory())->createMatcher()->match(
                $actualOperations,
                $expectedOperations
            )
        );

        $this->rootContext->ignoreState('Muchacuba\RechargeCard\Profile\Debt\Operation');
    }
}

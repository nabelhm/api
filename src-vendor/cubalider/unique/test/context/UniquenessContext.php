<?php

namespace Cubalider\Unique;

use Behat\Behat\Context\Environment\InitializedContextEnvironment;
use Behat\Behat\Context\SnippetAcceptingContext;
use Behat\Behat\Hook\Scope\BeforeScenarioScope;
use Behat\Gherkin\Node\PyStringNode;
use Behat\Symfony2Extension\Context\KernelAwareContext;
use Cubalider\Unique\Uniqueness as ConnectToUniquenessStorageWorker;
use Symfony\Component\HttpKernel\KernelInterface;
use PHPUnit_Framework_Assert as Assert;
use Coduo\PHPMatcher\Factory\SimpleFactory;
use Muchacuba\Context as RootContext;

/**
 * @author Nabel Hernandez <nabelhm@cubalider.com>
 * @author Yosmany Garcia <yosmanyga@gmail.com>
 */
class UniquenessContext implements SnippetAcceptingContext, KernelAwareContext
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
     * @When there are the following uniquenesses:
     *
     * @param PyStringNode $body
     */
    public function thereAreTheFollowingUniquenesses(PyStringNode $body)
    {
        $items = (array) json_decode($body->getRaw(), true);

        /** @var ConnectToStorageWorker $connectToStorageWorker */
        $connectToStorageWorker = $this->kernel
            ->getContainer()
            ->get('cubalider.connect_to_storage_worker');
        $connectToStorageWorker->connect()->drop();

        /** @var CreateUniquenessWorker $createUniquenessWorker */
        $createUniquenessWorker = $this->kernel
            ->getContainer()
            ->get('cubalider.create_uniqueness_worker');

        foreach ($items as $item) {
            $createUniquenessWorker->create(
                $item['id']
            );
        }
    }

    /**
     * @Given the system should have the following uniquenesses:
     */
    public function theSystemShouldHaveTheFollowingUniquenesses(PyStringNode $body)
    {
        /** @var CollectUniquenessTestWorker $collectUniquenessTestWorker */
        $collectUniquenessTestWorker = $this->kernel
            ->getContainer()
            ->get('cubalider.unique.collect_uniqueness_test_worker');

        Assert::assertTrue(
            (new SimpleFactory())->createMatcher()->match(
                iterator_to_array($collectUniquenessTestWorker->collect()),
                (array) json_decode($body->getRaw(), true)
            )
        );

        $this->rootContext->ignoreState('Cubalider\Uniqueness');
    }
}

<?php

namespace Muchacuba\RechargeCard;

use Behat\Behat\Context\Environment\InitializedContextEnvironment;
use Behat\Behat\Context\SnippetAcceptingContext;
use Behat\Behat\Hook\Scope\BeforeScenarioScope;
use Behat\Behat\Tester\Exception\PendingException;
use Behat\Gherkin\Node\PyStringNode;
use Behat\Symfony2Extension\Context\KernelAwareContext;
use Coduo\PHPMatcher\Factory\SimpleFactory;
use Muchacuba\RechargeCard\Profile\IncreaseDebtTestWorker;
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
     * @When the system has the following recharge card categories:
     *
     * @param PyStringNode $body
     */
    public function theSystemHasTheFollowingCategories(PyStringNode $body)
    {
        $items = (array) json_decode($body->getRaw(), true);

        /** @var CreateCategoryTestWorker $createCategoryTestWorker */
        $createCategoryTestWorker = $this->kernel
            ->getContainer()
            ->get('muchacuba.recharge_card.create_category_test_worker');

        foreach ($items as $item) {
            $createCategoryTestWorker->create(
                $item['id'],
                $item['name'],
                (int) $item['utility']
            );
        }
    }

    /**
     * @When the system has the following recharge card packages:
     *
     * @param PyStringNode $body
     */
    public function theSystemHasTheFollowingPackages(PyStringNode $body)
    {
        $items = (array) json_decode($body->getRaw(), true);

        /** @var CreatePackageTestWorker $createPackageTestWorker */
        $createPackageTestWorker = $this->kernel
            ->getContainer()
            ->get('muchacuba.recharge_card.create_package_test_worker');

        foreach ($items as $item) {
            $createPackageTestWorker->create(
                $item['id'],
                $item['name'],
                $item['category'],
                (int) $item['amount'],
                (int) $item['price']
            );
        }
    }

    /**
     * @When the system has the following recharge card cards:
     *
     * @param PyStringNode $body
     */
    public function theSystemHasTheFollowingCards(PyStringNode $body)
    {
        $items = (array) json_decode($body->getRaw(), true);

        /** @var CreateCardTestWorker $createCardTestWorker */
        $createCardTestWorker = $this->kernel
            ->getContainer()
            ->get('muchacuba.recharge_card.create_card_test_worker');

        foreach ($items as $item) {
            $createCardTestWorker->create(
                $item['code'],
                $item['category']
            );
        }
    }

    /**
     * @Given the recharge card profile :uniqueness has a debt of :debt CUC
     *
     * @param string $uniqueness
     * @param string $debt
     */
    public function theProfileHasDebt($uniqueness, $debt)
    {
        /** @var IncreaseDebtTestWorker $increaseDebtTestWorker */
        $increaseDebtTestWorker = $this->kernel
            ->getContainer()
            ->get('muchacuba.recharge_card.profile.increase_debt_test_worker');

        $increaseDebtTestWorker->increase(
            $uniqueness,
            (int) $debt,
            ''
        );
    }

    /**
     * @Then the system should have the following recharge profile for :uniqueness:
     *
     * @param string       $uniqueness
     * @param PyStringNode $body
     */
    public function theSystemShouldHaveTheFollowingProfile($uniqueness, PyStringNode $body)
    {
        $expectedProfile = (array) json_decode($body->getRaw(), true);

        /** @var PickProfileApiWorker $pickProfileApiWorker */
        $pickProfileApiWorker = $this->kernel
            ->getContainer()
            ->get('muchacuba.recharge_card.pick_profile_api_worker');

        $actualProfile = $pickProfileApiWorker->pick($uniqueness);

        Assert::assertTrue(
            (new SimpleFactory())->createMatcher()->match(
                $actualProfile,
                $expectedProfile
            )
        );
    }

    /**
     * @Then the system should have the following recharge card packages:
     *
     * @param PyStringNode $body
     */
    public function theSystemShouldHaveTheFollowingRechargeCardPackages(PyStringNode $body)
    {
        /** @var CollectPackagesTestWorker $collectPackagesTestWorker */
        $collectPackagesTestWorker = $this->kernel
            ->getContainer()
            ->get('muchacuba.recharge_card.collect_packages_test_worker');

        Assert::assertTrue(
            (new SimpleFactory())->createMatcher()->match(
                iterator_to_array($collectPackagesTestWorker->collect()),
                (array) json_decode($body->getRaw(), true)
            )
        );

        $this->rootContext->ignoreState('Muchacuba\RechargeCard\Package');
    }

    /**
     * @Given the system should have the following categories:
     *
     * @param PyStringNode $body
     */
    public function theSystemShouldHaveTheFollowingCategories(PyStringNode $body)
    {
        /** @var CollectCategoriesTestWorker $collectCategoriesTestWorker */
        $collectCategoriesTestWorker = $this->kernel
            ->getContainer()
            ->get('muchacuba.recharge_card.collect_categories_test_worker');

        Assert::assertTrue(
            (new SimpleFactory())->createMatcher()->match(
                iterator_to_array($collectCategoriesTestWorker->collect()),
                (array) json_decode($body->getRaw(), true)
            )
        );

        $this->rootContext->ignoreState('Muchacuba\RechargeCard\Category');
    }
}

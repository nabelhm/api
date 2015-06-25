<?php

namespace Muchacuba\Credit\Profile;

use Behat\Behat\Context\SnippetAcceptingContext;
use Behat\Symfony2Extension\Context\KernelAwareContext;
use Symfony\Component\HttpKernel\KernelInterface;

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
     * {@inheritdoc}
     */
    public function setKernel(KernelInterface $kernel)
    {
        $this->kernel = $kernel;
    }

    /**
     * @Given the credit profile :uniqueness has a balance of :balance CUC
     *
     * @param string $uniqueness
     * @param string $balance
     */
    public function theProfileHasBalance($uniqueness, $balance)
    {
        /** @var IncreaseBalanceTestWorker $increaseBalanceTestWorker */
        $increaseBalanceTestWorker = $this->kernel
            ->getContainer()
            ->get('muchacuba.credit.profile.increase_balance_test_worker');

        $increaseBalanceTestWorker->increase(
            $uniqueness,
            (int) $balance,
            ''
        );
    }
}

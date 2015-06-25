<?php


namespace Muchacuba\InfoSms\Profile;

use Behat\Behat\Context\SnippetAcceptingContext;
use Behat\Symfony2Extension\Context\KernelAwareContext;
use Symfony\Component\HttpKernel\KernelInterface;

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
     * {@inheritdoc}
     */
    public function setKernel(KernelInterface $kernel)
    {
        $this->kernel = $kernel;
    }

    /**
     * @Given the info sms profile :uniqueness has a balance of :balance sms
     *
     * @param string $uniqueness
     * @param string $balance
     */
    public function theProfileHasBalance($uniqueness, $balance)
    {
        /** @var SetBalanceTestWorker $setBalanceTestWorker */
        $setBalanceTestWorker = $this->kernel
            ->getContainer()
            ->get('muchacuba.info_sms.profile.set_balance_test_worker');

        $setBalanceTestWorker->set($uniqueness, (int) $balance);
    }
}

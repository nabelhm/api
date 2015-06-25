<?php


namespace Muchacuba\InfoSms\Subscription;

use Behat\Behat\Context\SnippetAcceptingContext;
use Behat\Symfony2Extension\Context\KernelAwareContext;
use Symfony\Component\HttpKernel\KernelInterface;

/**
 * @author Yosmany Garcia <yosmanyga@gmail.com>
 * @author Nabel Hernandez <nabelhm@cubalider.com>
 */
class SubscriptionByTopicStatContext implements SnippetAcceptingContext, KernelAwareContext
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
     * @Given the system create subscription stat
     */
    public function theSystemCreateSubscriptionStat()
    {
        /** @var CreateStatApiWorker $createStatApiWorker */
        $createStatApiWorker = $this->kernel
            ->getContainer()
            ->get('muchacuba.info_sms.subscription.create_stat_api_worker');

        $createStatApiWorker->create();
    }
}

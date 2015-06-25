<?php

namespace Muchacuba\ModelBundle\Controller\InfoSms\Me;

use JMS\DiExtraBundle\Annotation as DI;
use Muchacuba\InfoSms\CollectSubscriptionsApiWorker;
use Muchacuba\InfoSms\DeleteSubscriptionApiWorker;
use Muchacuba\ModelBundle\Util\JsonResponse;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorage;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;
use Sensio\Bundle\FrameworkExtraBundle\Configuration as Req;

/**
 * @author Nabel Hernandez <nabelhm@cubalider.com>
 * @author Yosmany Garcia <yosmanyga@gmail.com>
 */
class DeleteSubscriptionController
{
    /**
     * @var TokenStorage
     */
    private $tokenStorage;

    /**
     * @var DeleteSubscriptionApiWorker
     */
    private $deleteSubscriptionApiWorker;

    /**
     * @var CollectSubscriptionsApiWorker
     */
    private $collectSubscriptionsApiWorker;

    /**
     * @param TokenStorage                  $tokenStorage
     * @param DeleteSubscriptionApiWorker   $deleteSubscriptionApiWorker
     * @param CollectSubscriptionsApiWorker $collectSubscriptionsApiWorker
     *
     * @DI\InjectParams({
     *     "tokenStorage"                  = @DI\Inject("security.token_storage"),
     *     "deleteSubscriptionApiWorker"   = @DI\Inject("muchacuba.info_sms.delete_subscription_api_worker"),
     *     "collectSubscriptionsApiWorker" = @DI\Inject("muchacuba.info_sms.collect_subscriptions_api_worker"),
     * })
     */
    function __construct(
        TokenStorage $tokenStorage,
        DeleteSubscriptionApiWorker $deleteSubscriptionApiWorker,
        CollectSubscriptionsApiWorker $collectSubscriptionsApiWorker
    )
    {
        $this->tokenStorage = $tokenStorage;
        $this->deleteSubscriptionApiWorker = $deleteSubscriptionApiWorker;
        $this->collectSubscriptionsApiWorker = $collectSubscriptionsApiWorker;
    }

    /**
     * @Req\Route("/info-sms/me/delete-subscription/{mobile}")
     * @Req\Method({"POST"})
     *
     * @param string  $mobile
     *
     * @return JsonResponse
     */
    public function deleteAction($mobile)
    {
        /** @var UsernamePasswordToken $token */
        $token = $this->tokenStorage->getToken();
        $uniqueness = $token->getUsername();

        $this->deleteSubscriptionApiWorker->delete($mobile, $uniqueness);

        return new JsonResponse(
            $this->collectSubscriptionsApiWorker->collect($uniqueness)
        );
    }
}

<?php

namespace Muchacuba\ModelBundle\Controller\InfoSms\Me;

use Assert\Assertion;
use JMS\DiExtraBundle\Annotation as DI;
use Muchacuba\InfoSms\CollectSubscriptionsApiWorker;
use Muchacuba\InfoSms\Subscription\BlankAliasApiException;
use Muchacuba\InfoSms\NonExistentTopicApiException;
use Muchacuba\InfoSms\NoTopicsApiException;
use Muchacuba\InfoSms\UpdateSubscriptionApiWorker;
use Muchacuba\ModelBundle\Util\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorage;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;
use Sensio\Bundle\FrameworkExtraBundle\Configuration as Req;

/**
 * @author Nabel Hernandez <nabelhm@cubalider.com>
 * @author Yosmany Garcia <yosmanyga@gmail.com>
 */
class UpdateSubscriptionController
{
    /**
     * @var TokenStorage
     */
    private $tokenStorage;

    /**
     * @var UpdateSubscriptionApiWorker
     */
    private $updateSubscriptionApiWorker;

    /**
     * @var CollectSubscriptionsApiWorker
     */
    private $collectSubscriptionsApiWorker;

    /**
     * @param TokenStorage                  $tokenStorage
     * @param UpdateSubscriptionApiWorker   $updateSubscriptionApiWorker
     * @param CollectSubscriptionsApiWorker $collectSubscriptionsApiWorker
     *
     * @DI\InjectParams({
     *     "tokenStorage"                  = @DI\Inject("security.token_storage"),
     *     "updateSubscriptionApiWorker"   = @DI\Inject("muchacuba.info_sms.update_subscription_api_worker"),
     *     "collectSubscriptionsApiWorker" = @DI\Inject("muchacuba.info_sms.collect_subscriptions_api_worker"),
     * })
     */
    function __construct(
        TokenStorage $tokenStorage,
        UpdateSubscriptionApiWorker $updateSubscriptionApiWorker,
        CollectSubscriptionsApiWorker $collectSubscriptionsApiWorker
    )
    {
        $this->tokenStorage = $tokenStorage;
        $this->updateSubscriptionApiWorker = $updateSubscriptionApiWorker;
        $this->collectSubscriptionsApiWorker = $collectSubscriptionsApiWorker;
    }

    /**
     * @Req\Route("/info-sms/me/update-subscription/{mobile}")
     * @Req\Method({"POST"})
     *
     * @param string  $mobile
     * @param Request $request
     *
     * @return JsonResponse
     */
    public function updateAction($mobile, Request $request)
    {
        /** @var UsernamePasswordToken $token */
        $token = $this->tokenStorage->getToken();
        $uniqueness = $token->getUsername();

        $data = $request->request->all();

        foreach (array('alias', 'topics', 'active') as $key) {
            Assertion::keyExists($data, $key);
        }

        try {
            $this->updateSubscriptionApiWorker->update(
                $mobile,
                $uniqueness,
                $data['alias'],
                $data['topics'],
                $data['active']
            );
        } catch (BlankAliasApiException $e) {
            return new JsonResponse(
                array(
                    'code' => 'INFO_SMS.SUBSCRIPTION.BLANK_ALIAS'
                ),
                400
            );
        } catch (NoTopicsApiException $e) {
            return new JsonResponse(
                array(
                    'code' => 'INFO_SMS.SUBSCRIPTION.NO_TOPICS'
                ),
                400
            );
        } catch (NonExistentTopicApiException $e) {
            return new JsonResponse(
                array(
                    'code' => 'INFO_SMS.SUBSCRIPTION.NON_EXISTENT_TOPIC'
                ),
                400
            );
        }

        return new JsonResponse(
            $this->collectSubscriptionsApiWorker->collect($uniqueness)
        );
    }
}

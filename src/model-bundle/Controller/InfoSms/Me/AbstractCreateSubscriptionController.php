<?php

namespace Muchacuba\ModelBundle\Controller\InfoSms\Me;

use Assert\Assertion;
use Muchacuba\InfoSms\CreateSubscriptionApiWorker;
use Muchacuba\InfoSms\NonExistentTopicApiException;
use Muchacuba\InfoSms\Subscription\BlankAliasApiException;
use Muchacuba\InfoSms\Subscription\ExistentMobileApiException;
use Muchacuba\InfoSms\Subscription\InsufficientBalanceApiException;
use Muchacuba\InfoSms\Subscription\InvalidMobileApiException;
use Muchacuba\InfoSms\Subscription\NoResellPackageApiException;
use Muchacuba\InfoSms\NoTopicsApiException;
use Muchacuba\InfoSms\Subscription\TrialNotAcceptedApiException;
use Muchacuba\ModelBundle\Util\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorage;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;
use Sensio\Bundle\FrameworkExtraBundle\Configuration as Req;

/**
 * @author Nabel Hernandez <nabelhm@cubalider.com>
 * @author Yosmany Garcia <yosmanyga@gmail.com>
 */
abstract class AbstractCreateSubscriptionController
{
    /**
     * @var TokenStorage
     */
    protected $tokenStorage;

    /**
     * @var CreateSubscriptionApiWorker
     */
    private $createSubscriptionApiWorker;

    /**
     * @param TokenStorage                  $tokenStorage
     * @param CreateSubscriptionApiWorker   $createSubscriptionApiWorker
     */
    function __construct(
        TokenStorage $tokenStorage,
        CreateSubscriptionApiWorker $createSubscriptionApiWorker
    )
    {
        $this->tokenStorage = $tokenStorage;
        $this->createSubscriptionApiWorker = $createSubscriptionApiWorker;
    }

    /**
     * @param Request $request
     *
     * @return JsonResponse
     *
     * @throws \Assert\AssertionFailedException
     * @throws InvalidMobileApiException
     * @throws BlankAliasApiException
     * @throws NoTopicsApiException
     * @throws InsufficientBalanceApiException
     * @throws ExistentMobileApiException
     */
    protected function create(Request $request)
    {
        /** @var UsernamePasswordToken $token */
        $token = $this->tokenStorage->getToken();
        $uniqueness = $token->getUsername();

        $data = $request->request->all();

        foreach (array('mobile', 'alias', 'topics') as $key) {
            Assertion::keyExists($data, $key);
        }

        try {
            $this->createSubscriptionApiWorker->create(
                $data['mobile'],
                $uniqueness,
                $data['alias'],
                $data['topics'],
                $data['resellPackage']
            );
        } catch (InvalidMobileApiException $e) {
            return new JsonResponse(
                array(
                    'code' => 'INFO_SMS.SUBSCRIPTION.INVALID_MOBILE'
                ),
                400
            );
        } catch (BlankAliasApiException $e) {
            return new JsonResponse(
                array(
                    'code' => 'INFO_SMS.SUBSCRIPTION.BLANK_ALIAS'
                ),
                400
            );
        } catch (ExistentMobileApiException $e) {
            return new JsonResponse(
                array(
                    'code' => 'INFO_SMS.SUBSCRIPTION.EXISTENT_MOBILE'
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
        } catch (NoResellPackageApiException $e) {
            return new JsonResponse(
                array(
                    'code' => 'INFO_SMS.SUBSCRIPTION.NO_RESELL_PACKAGE'
                ),
                400
            );
        } catch (TrialNotAcceptedApiException $e) {
            return new JsonResponse(
                array(
                    'code' => 'INFO_SMS.SUBSCRIPTION.TRIAL_NOT_ACCEPTED'
                ),
                400
            );
        } catch (InsufficientBalanceApiException $e) {
            return new JsonResponse(
                array(
                    'code' => 'INFO_SMS.PROFILE.INSUFFICIENT_BALANCE'
                ),
                400
            );
        }

        return new JsonResponse();
    }
}

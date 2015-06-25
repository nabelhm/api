<?php

namespace Muchacuba\ModelBundle\Controller\User;

use Assert\Assertion;
use JMS\DiExtraBundle\Annotation as DI;
use Muchacuba\User\Account\AlreadyConsumedInvitationApiException;
use Muchacuba\User\Account\EmptyPasswordApiException;
use Muchacuba\User\Account\ExistentUsernameApiException;
use Muchacuba\User\Account\InvalidUsernameApiException;
use Muchacuba\User\Account\NonExistentInvitationApiException;
use Muchacuba\User\RegisterAccountApiWorker;
use Muchacuba\ModelBundle\Util\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration as Req;

/**
 * @author Nabel Hernandez <nabelhm@cubalider.com>
 * @author Yosmany Garcia <yosmanyga@gmail.com>
 */
class RegisterAccountController
{
    /**
     * @var RegisterAccountApiWorker
     */
    private $registerAccountApiWorker;

    /**
     * @param RegisterAccountApiWorker $registerAccountApiWorker
     *
     * @DI\InjectParams({
     *     "registerAccountApiWorker" = @DI\Inject("muchacuba.user.register_account_api_worker"),
     * })
     */
    function __construct(
        RegisterAccountApiWorker $registerAccountApiWorker
    )
    {
        $this->registerAccountApiWorker = $registerAccountApiWorker;
    }

    /**
     * @Req\Route("/user/register-account")
     * @Req\Method({"POST"})
     *
     * @param Request $request
     *
     * @return JsonResponse
     */
    public function registerAction(Request $request)
    {
        $data = $request->request->all();

        foreach (array('invitation', 'username', 'password') as $key) {
            Assertion::keyExists($data, $key);
        }

        try {
            $this->registerAccountApiWorker->register(
                $data['invitation'],
                $data['username'],
                $data['password']
            );
        } catch (NonExistentInvitationApiException $e) {
            return new JsonResponse(
                array(
                    'code' => 'USER.ACCOUNT.NON_EXISTENT_INVITATION'
                ),
                400
            );
        } catch (AlreadyConsumedInvitationApiException $e) {
            return new JsonResponse(
                array(
                    'code' => 'USER.ACCOUNT.ALREADY_CONSUMED_INVITATION'
                ),
                400
            );
        } catch (EmptyPasswordApiException $e) {
            return new JsonResponse(
                array(
                    'code' => 'USER.ACCOUNT.EMPTY_PASSWORD'
                ),
                400
            );
        } catch (InvalidUsernameApiException $e) {
            return new JsonResponse(
                array(
                    'code' => 'USER.ACCOUNT.INVALID_USERNAME'
                ),
                400
            );
        } catch (ExistentUsernameApiException $e) {
            return new JsonResponse(
                array(
                    'code' => 'USER.ACCOUNT.EXISTENT_USERNAME'
                ),
                400
            );
        }

        return new JsonResponse();
    }
}
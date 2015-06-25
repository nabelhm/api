<?php

namespace Muchacuba\ModelBundle\Controller\User;

use JMS\DiExtraBundle\Annotation as DI;
use Muchacuba\User\CollectAccountsApiWorker;
use Muchacuba\ModelBundle\Util\JsonResponse;
use Sensio\Bundle\FrameworkExtraBundle\Configuration as Req;

/**
 * @author Yosmany Garcia <yosmanyga@gmail.com>
 * @author Nabel Hernandez <nabelhm@cubalider.com>
 */
class CollectAccountsController
{
    /**
     * @var CollectAccountsApiWorker
     */
    private $collectAccountsApiWorker;

    /**
     * @param CollectAccountsApiWorker $collectAccountsApiWorker
     *
     * @DI\InjectParams({
     *     "collectAccountsApiWorker" = @DI\Inject("muchacuba.user.collect_accounts_api_worker"),
     * })
     */
    function __construct(
        CollectAccountsApiWorker $collectAccountsApiWorker
    )
    {
        $this->collectAccountsApiWorker = $collectAccountsApiWorker;
    }

    /**
     * @Req\Route("/user/collect-accounts")
     * @Req\Method({"GET"})
     *
     * @return JsonResponse
     */
    public function collectAction()
    {
        return new JsonResponse(
            $this->collectAccountsApiWorker->collect()
        );
    }
}

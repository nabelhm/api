<?php

namespace Muchacuba\ModelBundle\Controller\Privilege;

use JMS\DiExtraBundle\Annotation as DI;
use Muchacuba\Privilege\CollectRolesApiWorker;
use Muchacuba\ModelBundle\Util\JsonResponse;
use Sensio\Bundle\FrameworkExtraBundle\Configuration as Req;

/**
 * @author Nabel Hernandez <nabelhm@cubalider.com>
 * @author Yosmany Garcia <yosmanyga@gmail.com>
 */
class CollectRolesController
{
    /**
     * @var CollectRolesApiWorker
     */
    private $collectRolesApiWorker;

    /**
     * @param CollectRolesApiWorker $collectRolesApiWorker
     *
     * @DI\InjectParams({
     *     "collectRolesApiWorker" = @DI\Inject("muchacuba.privilege.collect_roles_api_worker"),
     * })
     */
    function __construct(
        CollectRolesApiWorker $collectRolesApiWorker
    )
    {
        $this->collectRolesApiWorker = $collectRolesApiWorker;
    }

    /**
     * @Req\Route("/privilege/collect-roles")
     * @Req\Method({"GET"})
     *
     * @return JsonResponse
     */
    public function collectAction()
    {
        return new JsonResponse(
            $this->collectRolesApiWorker->collect()
        );
    }
}

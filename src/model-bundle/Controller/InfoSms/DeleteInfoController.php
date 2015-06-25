<?php

namespace Muchacuba\ModelBundle\Controller\InfoSms;

use JMS\DiExtraBundle\Annotation as DI;
use Muchacuba\InfoSms\CollectInfosApiWorker;
use Muchacuba\InfoSms\DeleteInfoApiWorker;
use Muchacuba\ModelBundle\Util\JsonResponse;
use Sensio\Bundle\FrameworkExtraBundle\Configuration as Req;

/**
 * @author Nabel Hernandez <nabelhm@cubalider.com>
 * @author Yosmany Garcia <yosmanyga@gmail.com>
 */
class DeleteInfoController
{
    /**
     * @var DeleteInfoApiWorker
     */
    private $deleteInfoApiWorker;

    /**
     * @var CollectInfosApiWorker
     */
    private $collectInfosApiWorker;

    /**
     * @param DeleteInfoApiWorker   $deleteInfoApiWorker
     * @param CollectInfosApiWorker $collectInfosApiWorker
     *
     * @DI\InjectParams({
     *     "deleteInfoApiWorker"   = @DI\Inject("muchacuba.info_sms.delete__info_api_worker"),
     *     "collectInfosApiWorker" = @DI\Inject("muchacuba.info_sms.collect_infos_api_worker"),
     * })
     */
    function __construct(
        DeleteInfoApiWorker $deleteInfoApiWorker,
        CollectInfosApiWorker $collectInfosApiWorker
    )
    {
        $this->deleteInfoApiWorker = $deleteInfoApiWorker;
        $this->collectInfosApiWorker = $collectInfosApiWorker;
    }

    /**
     * @Req\Route("/info-sms/delete-info/{id}")
     * @Req\Method({"POST"})
     *
     * @param string $id
     *
     * @return JsonResponse
     */
    public function deleteAction($id)
    {
        $this->deleteInfoApiWorker->delete($id);

        return new JsonResponse(
            $this->collectInfosApiWorker->collect()
        );
    }
}
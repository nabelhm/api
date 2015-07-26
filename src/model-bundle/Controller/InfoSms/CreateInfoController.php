<?php

namespace Muchacuba\ModelBundle\Controller\InfoSms;

use Assert\Assertion;
use JMS\DiExtraBundle\Annotation as DI;
use Muchacuba\InfoSms\CollectInfosApiWorker;
use Muchacuba\InfoSms\CreateInfoApiWorker;
use Muchacuba\InfoSms\Info\BlankBodyApiException;
use Muchacuba\InfoSms\NonExistentTopicApiException;
use Muchacuba\InfoSms\NoTopicsApiException;
use Muchacuba\ModelBundle\Util\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration as Req;

/**
 * @author Nabel Hernandez <nabelhm@cubalider.com>
 * @author Yosmany Garcia <yosmanyga@gmail.com>
 */
class CreateInfoController
{
    /**
     * @var CreateInfoApiWorker
     */
    private $createInfoApiWorker;

    /**
     * @var CollectInfosApiWorker
     */
    private $collectInfosApiWorker;

    /**
     * @param CreateInfoApiWorker   $createInfoApiWorker
     * @param CollectInfosApiWorker $collectInfosApiWorker
     *
     * @DI\InjectParams({
     *     "createInfoApiWorker"   = @DI\Inject("muchacuba.info_sms.create_info_api_worker"),
     *     "collectInfosApiWorker" = @DI\Inject("muchacuba.info_sms.collect_infos_api_worker")
     * })
     */
    function __construct(
        CreateInfoApiWorker $createInfoApiWorker,
        CollectInfosApiWorker $collectInfosApiWorker
    )
    {
        $this->createInfoApiWorker = $createInfoApiWorker;
        $this->collectInfosApiWorker = $collectInfosApiWorker;
    }

    /**
     * @Req\Route("/info-sms/create-info")
     * @Req\Method({"POST"})
     *
     * @param Request $request
     *
     * @return JsonResponse
     */
    public function createAction(Request $request)
    {
        $data = $request->request->all();

        foreach (array('body', 'topics') as $key) {
            Assertion::keyExists($data, $key);
        }

        try {
            $this->createInfoApiWorker->create(
                $data['body'],
                $data['topics']
            );
        } catch (BlankBodyApiException $e) {
            return new JsonResponse(
                array(
                    'code' => 'INFO_SMS.INFO.BLANK_BODY'
                ),
                400
            );
        } catch (NoTopicsApiException $e) {
            return new JsonResponse(
                array(
                    'code' => 'INFO_SMS.INFO.NO_TOPICS'
                ),
                400
            );
        } catch (NonExistentTopicApiException $e) {
            return new JsonResponse(
                array(
                    'code' => 'INFO_SMS.INFO.NON_EXISTENT_TOPIC'
                ),
                400
            );
        }

        return new JsonResponse(
            $this->collectInfosApiWorker->collect()
        );
    }
}
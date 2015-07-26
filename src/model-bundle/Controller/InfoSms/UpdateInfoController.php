<?php

namespace Muchacuba\ModelBundle\Controller\InfoSms;

use Assert\Assertion;
use JMS\DiExtraBundle\Annotation as DI;
use Muchacuba\InfoSms\CollectInfosApiWorker;
use Muchacuba\InfoSms\Info\BlankBodyApiException;
use Muchacuba\InfoSms\NonExistentTopicApiException;
use Muchacuba\InfoSms\NoTopicsApiException;
use Muchacuba\InfoSms\UpdateInfoApiWorker;
use Muchacuba\ModelBundle\Util\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration as Req;

/**
 * @author Nabel Hernandez <nabelhm@cubalider.com>
 * @author Yosmany Garcia <yosmanyga@gmail.com>
 */
class UpdateInfoController
{
    /**
     * @var UpdateInfoApiWorker
     */
    private $updateInfoApiWorker;

    /**
     * @var CollectInfosApiWorker
     */
    private $collectInfosApiWorker;

    /**
     * @param UpdateInfoApiWorker   $updateInfoApiWorker
     * @param CollectInfosApiWorker $collectInfosApiWorker
     *
     * @DI\InjectParams({
     *     "updateInfoApiWorker"   = @DI\Inject("muchacuba.info_sms.update__info_api_worker"),
     *     "collectInfosApiWorker" = @DI\Inject("muchacuba.info_sms.collect_infos_api_worker"),
     * })
     */
    function __construct(
        UpdateInfoApiWorker $updateInfoApiWorker,
        CollectInfosApiWorker $collectInfosApiWorker
    )
    {
        $this->updateInfoApiWorker = $updateInfoApiWorker;
        $this->collectInfosApiWorker = $collectInfosApiWorker;
    }

    /**
     * @Req\Route("/info-sms/update-info/{id}")
     * @Req\Method({"POST"})
     *
     * @param string  $id
     * @param Request $request
     *
     * @return JsonResponse
     */
    public function updateAction($id, Request $request)
    {
        $data = $request->request->all();

        foreach (array('body', 'topics') as $key) {
            Assertion::keyExists($data, $key);
        }

        try {
            $this->updateInfoApiWorker->update(
                $id,
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
<?php

namespace Muchacuba\ModelBundle\Controller\InfoSms\Message;

use JMS\DiExtraBundle\Annotation as DI;
use Knp\Snappy\GeneratorInterface as PdfGenerator;
use Muchacuba\InfoSms\CollectTopicsApiWorker;
use Muchacuba\InfoSms\Message\CollectLatestStatsGroupedByTopicApiWorker;
use Muchacuba\ModelBundle\Util\JsonResponse;
use Sensio\Bundle\FrameworkExtraBundle\Configuration as Req;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Templating\EngineInterface;

/**
 * @author Nabel Hernandez <nabelhm@cubalider.com>
 * @author Yosmany Garcia <yosmanyga@gmail.com>
 */
class GenerateLatestStatsGroupedByTopicController
{
    /**
     * @var PdfGenerator
     */
    private $pdfGenerator;

    /**
     * @var CollectTopicsApiWorker
     */
    private $collectTopicsApiWorker;

    /**
     * @var CollectLatestStatsGroupedByTopicApiWorker
     */
    private $collectLatestStatsGroupedByTopic;

    /**
     * @var EngineInterface
     */
    private $templateEngine;

    /**
     * @param PdfGenerator                              $pdfGenerator
     * @param CollectTopicsApiWorker                    $collectTopicsApiWorker
     * @param CollectLatestStatsGroupedByTopicApiWorker $collectLatestStatsGroupedByTopic
     * @param EngineInterface                           $templateEngine
     *
     * @DI\InjectParams({
     *     "pdfGenerator"                     = @DI\Inject("knp_snappy.pdf"),
     *     "collectTopicsApiWorker"           = @DI\Inject("muchacuba.info_sms.collect_topics_api_worker"),
     *     "collectLatestStatsGroupedByTopic" = @DI\Inject("muchacuba.info_sms.message.collect_latest_stats_grouped_by_topic_api_worker"),
     *     "templateEngine"                   = @DI\Inject("templating")
     * })
     */
    function __construct(
        PdfGenerator $pdfGenerator,
        CollectTopicsApiWorker $collectTopicsApiWorker,
        CollectLatestStatsGroupedByTopicApiWorker $collectLatestStatsGroupedByTopic,
        EngineInterface $templateEngine
    )
    {
        $this->pdfGenerator = $pdfGenerator;
        $this->collectTopicsApiWorker = $collectTopicsApiWorker;
        $this->collectLatestStatsGroupedByTopic = $collectLatestStatsGroupedByTopic;
        $this->templateEngine = $templateEngine;
    }

    /**
     * @Req\Route("/info-sms/message/generate-latest-stats")
     * @Req\Method({"GET"})
     *
     * @return JsonResponse
     */
    public function generateAction()
    {
        $html = $this->templateEngine->render(
            "@MuchacubaModel/InfoSms\\Message\\GenerateLatestStatsGroupedByTopic\\generate.html.twig",
            [
                'topics' => $this->collectTopicsApiWorker->collect(),
                'stats' => $this->collectLatestStatsGroupedByTopic->collect()
            ]
        );

        return new Response(
            $this->pdfGenerator->getOutputFromHtml($html),
            200,
            array(
                'Content-Type' => 'application/pdf',
                'Content-Disposition' => 'attachment; filename="ultimas-noticias.pdf"'
            )
        );
    }
}
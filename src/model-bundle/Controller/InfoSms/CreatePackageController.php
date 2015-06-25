<?php

namespace Muchacuba\ModelBundle\Controller\InfoSms;

use Assert\Assertion;
use JMS\DiExtraBundle\Annotation as DI;
use Muchacuba\InfoSms\CollectPackagesApiWorker;
use Muchacuba\InfoSms\CreatePackageApiWorker;
use Muchacuba\ModelBundle\Util\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration as Req;

/**
 * @author Nabel Hernandez <nabelhm@cubalider.com>
 * @author Yosmany Garcia <yosmanyga@gmail.com>
 */
class CreatePackageController
{
    /**
     * @var CreatePackageApiWorker
     */
    private $createPackageApiWorker;

    /**
     * @var CollectPackagesApiWorker
     */
    private $collectPackagesApiWorker;

    /**
     * @param CreatePackageApiWorker   $createPackageApiWorker
     * @param CollectPackagesApiWorker $collectPackagesApiWorker
     *
     * @DI\InjectParams({
     *     "createPackageApiWorker"   = @DI\Inject("muchacuba.info_sms.create_package_api_worker"),
     *     "collectPackagesApiWorker" = @DI\Inject("muchacuba.info_sms.collect_packages_api_worker"),
     * })
     */
    function __construct(
        CreatePackageApiWorker $createPackageApiWorker,
        CollectPackagesApiWorker $collectPackagesApiWorker
    )
    {
        $this->createPackageApiWorker = $createPackageApiWorker;
        $this->collectPackagesApiWorker = $collectPackagesApiWorker;
    }

    /**
     * @Req\Route("/info-sms/create-package")
     * @Req\Method({"POST"})
     *
     * @param Request $request
     *
     * @return JsonResponse
     */
    public function createAction(Request $request)
    {
        $data = $request->request->all();

        foreach (array('name', 'amount', 'price') as $key) {
            Assertion::keyExists($data, $key);
        }

        $this->createPackageApiWorker->create(
            $data['name'],
            $data['amount'],
            $data['price']
        );

        return new JsonResponse(
            $this->collectPackagesApiWorker->collect()
        );
    }
}
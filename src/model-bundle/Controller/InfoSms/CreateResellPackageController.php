<?php

namespace Muchacuba\ModelBundle\Controller\InfoSms;

use Assert\Assertion;
use JMS\DiExtraBundle\Annotation as DI;
use Muchacuba\InfoSms\CollectResellPackagesApiWorker;
use Muchacuba\InfoSms\CreateResellPackageApiWorker;
use Muchacuba\ModelBundle\Util\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration as Req;

/**
 * @author Nabel Hernandez <nabelhm@cubalider.com>
 * @author Yosmany Garcia <yosmanyga@gmail.com>
 */
class CreateResellPackageController
{
    /**
     * @var CreateResellPackageApiWorker
     */
    private $createResellPackageApiWorker;

    /**
     * @var CollectResellPackagesApiWorker
     */
    private $collectResellPackagesApiWorker;

    /**
     * @param CreateResellPackageApiWorker   $createResellPackageApiWorker
     * @param CollectResellPackagesApiWorker $collectResellPackagesApiWorker
     *
     * @DI\InjectParams({
     *     "createResellPackageApiWorker"   = @DI\Inject("muchacuba.info_sms.create_resell_package_api_worker"),
     *     "collectResellPackagesApiWorker" = @DI\Inject("muchacuba.info_sms.collect_resell_packages_api_worker"),
     * })
     */
    function __construct(
        CreateResellPackageApiWorker $createResellPackageApiWorker,
        CollectResellPackagesApiWorker $collectResellPackagesApiWorker
    )
    {
        $this->createResellPackageApiWorker = $createResellPackageApiWorker;
        $this->collectResellPackagesApiWorker = $collectResellPackagesApiWorker;
    }

    /**
     * @Req\Route("/info-sms/create-resell-package")
     * @Req\Method({"POST"})
     *
     * @param Request $request
     *
     * @return JsonResponse
     */
    public function createAction(Request $request)
    {
        $data = $request->request->all();

        foreach (array('amount', 'price') as $key) {
            Assertion::keyExists($data, $key);
        }

        $this->createResellPackageApiWorker->create(
            (int) $data['amount'],
            (int) $data['price'],
            $data['description']
        );

        return new JsonResponse(
            $this->collectResellPackagesApiWorker->collect()
        );
    }
}
<?php

namespace Muchacuba\ModelBundle\Controller\InfoSms;

use Assert\Assertion;
use JMS\DiExtraBundle\Annotation as DI;
use Muchacuba\InfoSms\CollectResellPackagesApiWorker;
use Muchacuba\InfoSms\UpdateResellPackageApiWorker;
use Muchacuba\ModelBundle\Util\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration as Req;

/**
 * @author Nabel Hernandez <nabelhm@cubalider.com>
 * @author Yosmany Garcia <yosmanyga@gmail.com>
 */
class UpdateResellPackageController
{
    /**
     * @var UpdateResellPackageApiWorker
     */
    private $updateResellPackageApiWorker;

    /**
     * @var CollectResellPackagesApiWorker
     */
    private $collectResellPackagesApiWorker;

    /**
     * @param UpdateResellPackageApiWorker   $updateResellPackageApiWorker
     * @param CollectResellPackagesApiWorker $collectResellPackagesApiWorker
     *
     * @DI\InjectParams({
     *     "updateResellPackageApiWorker"   = @DI\Inject("muchacuba.info_sms.update_resell_package_api_worker"),
     *     "collectResellPackagesApiWorker" = @DI\Inject("muchacuba.info_sms.collect_resell_packages_api_worker"),
     * })
     */
    function __construct(
        UpdateResellPackageApiWorker $updateResellPackageApiWorker,
        CollectResellPackagesApiWorker $collectResellPackagesApiWorker
    )
    {
        $this->updateResellPackageApiWorker = $updateResellPackageApiWorker;
        $this->collectResellPackagesApiWorker = $collectResellPackagesApiWorker;
    }

    /**
     * @Req\Route("/info-sms/update-resell-package/{id}")
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

        foreach (array('amount', 'price') as $key) {
            Assertion::keyExists($data, $key);
        }

        $this->updateResellPackageApiWorker->update(
            $id,
            (int) $data['amount'],
            (int) $data['price'],
            $data['description']
        );

        return new JsonResponse(
            $this->collectResellPackagesApiWorker->collect()
        );
    }
}
<?php

namespace Muchacuba\Invitation;

use Cubalider\CodeGenerator;
use Muchacuba\Invitation\Card\ConnectToStorageInternalWorker;
use JMS\DiExtraBundle\Annotation as Di;

/**
 * @author Yosmany Garcia <yosmanyga@gmail.com>
 *
 * @Di\Service()
 */
class GenerateCardsInternalWorker
{
    /**
     * @var ConnectToStorageInternalWorker
     */
    private $connectToStorageInternalWorker;

    /**
     * @var CodeGenerator
     */
    private $codeGenerator;

    /**
     * @param ConnectToStorageInternalWorker $connectToStorageInternalWorker
     * @param CodeGenerator                  $codeGenerator
     *
     * @Di\InjectParams({
     *     "connectToStorageInternalWorker" = @Di\Inject("muchacuba.invitation.card.connect_to_storage_internal_worker"),
     *     "codeGenerator"                  = @Di\Inject("cubalider.code_generator")
     * })
     */
    function __construct(
        ConnectToStorageInternalWorker $connectToStorageInternalWorker,
        CodeGenerator $codeGenerator
    )
    {
        $this->connectToStorageInternalWorker = $connectToStorageInternalWorker;
        $this->codeGenerator = $codeGenerator;
    }

    /**
     * Creates given amount of cards, of given role.
     *
     * @param string  $role
     * @param integer $amount
     *
     * @return string[] the already created card codes
     */
    public function generate($role, $amount)
    {
        $codes = [];
        for ($i = 1; $i <= $amount; $i++) {
            $code = $this->generateCode();

            $this->connectToStorageInternalWorker->connect()->insert(array(
                'code' => $code,
                'role' => $role,
                'consumed' => false
            ));

            $codes[] = $code;
        }

        return $codes;
    }

    /**
     * Generates a code, verifying that no card use it.
     *
     * @return string
     */
    private function generateCode()
    {
        $code = $this->codeGenerator->generate('xx-xx-xx');

        while ($this->connectToStorageInternalWorker->connect()->findOne(['code' => $code])) {
            $code = $this->codeGenerator->generate();
        }

        return $code;
    }
}
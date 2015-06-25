<?php

namespace Muchacuba\Privilege\Role;

use JMS\DiExtraBundle\Annotation as Di;

/**
 * @author Yosmany Garcia <yosmanyga@gmail.com>
 *
 * @Di\Service()
 */
class ConnectToStorageInternalWorker
{
    /**
     * @var string[]
     */
    private $roles = [
        [
            'code' => 'ROLE_ADMIN',
            'title' => 'Administrador'
        ],
        [
            'code' => 'ROLE_INFO_SMS_RESELLER',
            'title' => 'Vendedor de noticias por sms'
        ],
        [
            'code' => 'ROLE_INFO_SMS_JOURNALIST',
            'title' => 'Editor de noticias'
        ],
        [
            'code' => 'ROLE_RECHARGE_CARD_RESELLER',
            'title' => 'Vendedor de tarjetas de recarga'
        ]
    ];

    /**
     * @return string[]
     */
    public function find()
    {
        return $this->roles;
    }

    /**
     * @param string $code
     * 
     * @return string[]
     */
    public function findOne($code)
    {
        foreach ($this->roles as $role) {
            if ($role['code'] == $code) {
                return $role;
            }
        }

        return false;
    }
}
<?php

namespace Muchacuba\ModelBundle\User;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Http\Authentication\AuthenticationFailureHandlerInterface;

class FailureHandler implements AuthenticationFailureHandlerInterface
{
    /**
     * {@inheritdoc}
     */
    public function onAuthenticationFailure(Request $request, AuthenticationException $exception)
    {
        return new JsonResponse(
            array(
                'message' => 'Los datos de la cuenta no son correctos.'
            ),
            400
        );
    }
}
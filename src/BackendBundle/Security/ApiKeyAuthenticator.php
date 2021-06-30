<?php

namespace App\BackendBundle\Security;

use App\BackendBundle\Repository\ApiKeyRepository;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\Exception\CustomUserMessageAuthenticationException;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Guard\AbstractGuardAuthenticator;

class ApiKeyAuthenticator extends AbstractGuardAuthenticator
{
    /**
     * @var ApiKeyRepository
     */
    private $apiKeyRepository;

    public function __construct(ApiKeyRepository $apiKeyRepository)
    {
        $this->apiKeyRepository = $apiKeyRepository;
    }

    public function supports(Request $request): ?bool
    {
        return $request->headers->has('Authorization') && 0===strpos($request->headers->get('Authorization'),'Bearer ');
    }

    public function onAuthenticationSuccess(Request $request, TokenInterface $token, string $firewallName): ?Response
    {
        return null;
    }

    public function onAuthenticationFailure(Request $request, AuthenticationException $exception): ?Response
    {
        $data = [
            'message' => strtr($exception->getMessageKey(), $exception->getMessageData())
        ];

        return new JsonResponse($data, Response::HTTP_FORBIDDEN);
    }

    public function start(Request $request, AuthenticationException $authException = null): JsonResponse
    {
        $data = [
            'message' => 'Authentication Required'
        ];
        return new JsonResponse($data, Response::HTTP_UNAUTHORIZED);
    }

    public function getCredentials(Request $request)
    {
        $token=$request->headers->get('Authorization');
        return substr($token,7);
    }

    public function getUser($credentials, UserProviderInterface $userProvider)
    {
        $token=$this->apiKeyRepository->findOneBy(array('code'=>$credentials,'valid'=>true));
        if(!$token)
        {
            throw new CustomUserMessageAuthenticationException('Invalid API Token');
        }

        return $token->getUser();
    }

    public function checkCredentials($credentials, UserInterface $user): bool
    {
        return true;
    }

    public function supportsRememberMe(): bool
    {
        return false;
    }
}

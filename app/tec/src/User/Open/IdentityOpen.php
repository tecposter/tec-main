<?php
namespace Tec\User\Open;

use Gap\Http\JsonResponse;
use Tec\User\Service\IdTokenService;
use Tec\User\Service\IdentityService;
use Tec\User\Service\AccessTokenService;
use Gap\Http\Cookie;

class IdentityOpen extends OpenBase
{
    public function access(): JsonResponse
    {
        /*
        $cookies = $this->request->cookies;
        $idTokenStr = $cookies->get('idToken');

        $idTokenService = $this->getIdTokenService();
        $idToken = $idTokenService->strToToken($idTokenStr);
        if ($idToken->isExpired()) {
            throw new \Exception('idToken expired');
        }
        if (!$idTokenService->verifyToken($idToken)) {
            throw new \Exception('verify token failed');
        }
        $identityCode = $idTokenService->extractIdentityCode($idToken);
         */
        $identityCode = $this->getIdentityCode();

        $identityService = $this->getIdentityService();
        $identityData = $identityService->fetchData($identityCode);
        $accessToken = $identityService->access($identityData['userId']);


        return new JsonResponse($accessToken);
    }

    public function refreshIdToken(): JsonResponse
    {
        $identityCode = $this->getIdentityCode();
        $identityService = $this->getIdentityService();
        $identityData = $identityService->fetchData($identityCode);
        $ttl = new \DateInterval('P1M');
        $identity = $this->getIdentityService()->create(
            $identityData,
            $ttl
        );

        $idToken = $this->getIdTokenService()->create($identity->code, $ttl);
        $response = new JsonResponse([
            'status' => 'ok'
        ]);
        $response->headers->setCookie($this->idTokenCookie($idToken, $ttl));
        $response->headers->setCookie($this->loginedCookie($ttl));
        return $response;
    }

    public function refreshAccessToken(): JsonResponse
    {
        $accessToken = $this->request->attributes->get('accessToken');
        $post = $this->request->request;

        if ($post->get('refresh') !== $accessToken->refresh->getHex()) {
            throw new \Exception('refresh code not match');
        }

        $refreshed = $this->getAccessTokenService()->refresh($accessToken);
        return new JsonResponse($refreshed);
    }

    private function getIdentityCode()
    {
        $cookies = $this->request->cookies;
        $idTokenStr = $cookies->get('idToken');

        $idTokenService = $this->getIdTokenService();
        $idToken = $idTokenService->strToToken($idTokenStr);
        if ($idToken->isExpired()) {
            throw new \Exception('idToken expired');
        }
        if (!$idTokenService->verifyToken($idToken)) {
            throw new \Exception('verify token failed');
        }
        $identityCode = $idTokenService->extractIdentityCode($idToken);
        return $identityCode;
    }

    private function getIdTokenService(): IdTokenService
    {
        return new IdTokenService($this->getApp());
    }

    private function getAccessTokenService(): AccessTokenService
    {
        return new AccesstokenService($this->getApp());
    }

    private function getIdentityService(): IdentityService
    {
        return new IdentityService($this->getApp());
    }

    private function idTokenCookie($idToken, $ttl): Cookie
    {
        $now = new \DateTime();
        $expired = $now->add($ttl)->getTimeStamp();

        //$expire = 0;
        $path = '/';
        $domain = '.' . $this->config->str('baseHost');
        $secure = true;
        $httpOnly = true;
        return new Cookie(
            'idToken',
            $idToken,
            $expired,
            $path,
            $domain,
            $secure,
            $httpOnly
        );
    }

    private function loginedCookie($ttl): Cookie
    {
        $now = new \DateTime();
        $expired = $now->add($ttl)->getTimeStamp();
        $path = '/';
        $domain = '.' . $this->config->str('baseHost');
        $secure = true;
        $httpOnly = false;
        return new Cookie(
            'logined',
            'true',
            $expired,
            $path,
            $domain,
            $secure,
            $httpOnly
        );
    }
}

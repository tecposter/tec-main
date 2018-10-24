<?php
namespace Tec\User\Open;

use Gap\Http\JsonResponse;
use Tec\User\Service\IdTokenService;
use Tec\User\Service\IdentityService;

class IdentityOpen extends OpenBase
{
    public function access(): JsonResponse
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

        $identityService = $this->getIdentityService();
        $identityData = $identityService->fetchData($identityCode);
        $accessToken = $identityService->access($identityData['userId']);


        return new JsonResponse($accessToken);
    }

    private function getIdTokenService(): IdTokenService
    {
        return new IdTokenService($this->getApp());
    }

    private function getIdentityService(): IdentityService
    {
        return new IdentityService($this->getApp());
    }
}

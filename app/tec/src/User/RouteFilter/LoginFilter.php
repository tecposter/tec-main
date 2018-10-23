<?php
namespace Tec\User\RouteFilter;

use Tec\User\Service\IdentityService;
use Tec\User\Service\IdTokenService;

class LoginFilter extends RouteFilterBase
{
    public function filter(): void
    {
        $session = $this->request->getSession();
        if ($session->get('userId')) {
            return;
        }

        $cookies = $this->request->cookies;
        $idTokenStr = $cookies->get('idToken');
        if (!$idTokenStr) {
            $this->throwExeption('not-login');
        }

        $idTokenService = $this->getIdTokenService();
        $idToken = $idTokenService->strToToken($idTokenStr);
        if ($idToken->isExpired()) {
            $this->throwExeption('idToken expired');
        }
        if (!$idTokenService->verifyToken($idToken)) {
            $this->throwExeption('verify idToken failed');
        }

        $identityCode = $idTokenService->extractIdentityCode($idToken);
        $data = $this->getIdentityService()->fetchData($identityCode);
        $session->set('userId', $data['userId']);
    }

    private function throwExeption(string $msg): void
    {
        throw new \Exception($msg);
    }

    private function getIdentityService(): IdentityService
    {
        return new IdentityService($this->getApp());
    }

    private function getIdTokenService(): IdTokenService
    {
        return new IdTokenService($this->getApp());
    }
}

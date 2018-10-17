<?php
namespace Tec\User\RouteFilter;

use Tec\User\Service\IdentityService;

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

        $identityService = new IdentityService($this->getApp());
        $idTokenDto = $identityService->strToToken($idTokenStr);

        if ($idTokenDto->isExpired()) {
            $this->throwExeption('idToken expired');
        }

        if (!$identityService->verifyToken($idTokenDto)) {
            $this->throwExeption('verify idToken failed');
        }

        $sub = $idTokenDto->getClaim('sub');
        $arr = explode('|', $sub);
        if (!isset($arr[1])) {
            $this->throwExeption('sub format is not correct');
        }

        $codeStr = $arr[1];
        $userDto = $identityService->fetchUserByCode($codeStr);
        $session->set('userId', $userDto->userId);
    }

    private function throwExeption(string $msg): void
    {
        throw new \Exception($msg);
    }
}

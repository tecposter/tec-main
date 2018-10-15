<?php
namespace Tec\User\RouteFilter;

use Tec\User\Service\IdentityService;

class LoginFilter extends RouteFilterBase
{
    public function filter(): void
    {
        $session = $this->request->getSession();
        if ($session->get('identityZcodeStr')) {
            return;
        }

        $cookies = $this->request->cookies;
        $idTokenStr = $cookies->get('idToken');
        if (!$idTokenStr) {
            throw new \Exception('not-login');
        }

        $openIdService = new IdentityService($this->getApp());
        $idToken = $openIdService->strToToken($idTokenStr);
        if (!$openIdService->verifyToken($idToken)) {
            throw new \Exception('verify idToken failed');
        }

        $sub = $idToken->getClaim('sub');
        $arr = explode('|', $sub);
        if (!isset($arr[1])) {
            throw new \Exception('sub format is not correct');
        }
        $identityZcodeStr = $arr[1];
        $session->set('identityZcodeStr', $identityZcodeStr);
    }
}

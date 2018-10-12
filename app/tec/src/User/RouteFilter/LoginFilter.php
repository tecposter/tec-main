<?php
namespace Tec\User\RouteFilter;

use Tec\User\Service\OpenIdService;

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
            throw new \Exception('not-login');
        }

        $openIdService = new OpenIdService($this->getApp());
        $idToken = $openIdService->strToToken($idTokenStr);
        if (!$openIdService->verifyToken($idToken)) {
            throw new \Exception('verify idToken failed');
        }

        $sub = $idToken->getClaim('sub');
        $arr = explode('|', $sub);
        if (!isset($arr[1])) {
            throw new \Exception('sub format is not correct');
        }
        $userId = $arr[1];
        $session->set('userId', $userId);
    }
}

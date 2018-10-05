<?php
namespace Tec\Portal\OAuth2\RouteFilter;

use Tec\Portal\OAuth2\Service\OpenIdService;

class LoginFilter extends RouteFilterBase
{
    public function filter(): void
    {
        $route = $this->getRoute();
        $access = $route->getAccess();

        if ($access !== 'login') {
            return;
        }

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
        $idToken = $openIdService->parseIdTokenStr($idTokenStr);
        if (!$idToken) {
            throw new \Exception('login filter failed');
        }

        if (!$openIdService->verifyIdToken($idToken)) {
            throw new \Exception('login filter failed');
        }

        $sub = $idToken->getClaim('sub');
        list($vendor, $userId) = explode('|', $sub);
        $session->set('userId', $userId);
    }
}

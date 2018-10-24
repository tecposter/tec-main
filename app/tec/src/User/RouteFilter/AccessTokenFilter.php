<?php
namespace Tec\User\RouteFilter;

use Tec\User\Service\AccessTokenService;

class AccessTokenFilter extends RouteFilterBase
{
    const PATTERN = '/bearer ([a-z0-9]+)/i';

    public function filter(): void
    {
        $authorization = trim($this->request->headers->get('authorization', ''));
        if (empty($authorization)) {
            throw new \Exception('Cannot find authorization header in request');
        }

        if (!preg_match(self::PATTERN, $authorization, $matches)) {
            throw new \Exception('The format of authorization header is incorrect');
        }

        $token = trim($matches[1]);
        if (empty($token)) {
            throw new \Exception('Token cannot be empty');
        }

        $accessToken = $this->getAccessTokenService()->fetch($token);
        if (is_null($accessToken)) {
            throw new \Exception('Cannot find access token');
        }
        if ($accessToken->isExpired()) {
            throw new \Exception('Access Token has been expired');
        }

        $this->request->attributes->set('accessToken', $accessToken);
    }

    private function getAccessTokenService(): AccessTokenService
    {
        return new AccessTokenService($this->getApp());
    }
}

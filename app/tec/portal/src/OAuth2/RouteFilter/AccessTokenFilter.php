<?php
namespace Tec\Portal\OAuth2\RouteFilter;

use Tec\Portal\OAuth2\Service\OpenIdService;
use Gap\Dto\DateTime;

class AccessTokenFilter extends RouteFilterBase
{
    private $prefix = 'bearer ';

    public function filter()
    {
        $route = $this->getRoute();
        $access = $route->getAccess();
        if ($access !== 'accessToken') {
            return;
        }

        $authorization = $this->request->headers->get('authorization');

        if (stripos($authorization, $this->prefix) !== 0) {
            throw new \Exception('Error format for authorization header');
        }

        $token = substr($authorization, strlen($this->prefix));

        $accessToken = (new OpenIdService($this->getApp()))
            ->fetchAccessTokenByToken($token);

        if (is_null($accessToken)) {
            throw new \Exception('access token filter failed');
        }

        $now = new DateTime();
        $expired = new DateTime($accessToken->expired);
        if ($now > $expired) {
            throw new \Exception('access token expired');
        }
    }
}

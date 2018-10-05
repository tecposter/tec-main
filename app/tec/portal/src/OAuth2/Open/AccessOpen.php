<?php
namespace Tec\Portal\OAuth2\Open;

use Gap\Http\JsonResponse;
use Tec\Portal\OAuth2\Service\OpenIdService;

class AccessOpen extends OpenBase
{
    private $openIdService;

    public function accessByIdToken(): JsonResponse
    {
        $cookies = $this->request->cookies;
        $idToken = $cookies->get('idToken');
        $appCode = $this->request->request->get('appCode');

        $accessToken = $this->getOpenIdService()->accessTokenByIdToken($idToken, $appCode);

        return new JsonResponse($accessToken);
    }

    private function getOpenIdService(): OpenIdService
    {
        if ($this->openIdService) {
            return $this->openIdService;
        }

        $this->openIdService = new OpenIdService($this->app);
        return $this->openIdService;
    }
}

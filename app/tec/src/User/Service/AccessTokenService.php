<?php
namespace Tec\User\Service;

use Tec\User\Dto\AccessTokenDto;
use Tec\User\Repo\AccessTokenRepo;

class AccessTokenService extends ServiceBase
{
    public function fetch(string $token): ?AccessTokenDto
    {
        return $this->getAccessTokenRepo()->fetch($token);
    }

    public function refresh(AccessTokenDto $accessToken): AccessTokenDto
    {
        return $this->getAccessTokenRepo()->create(
            $accessToken->userId,
            $accessToken->appId,
            $accessToken->getTtl()
        );
    }

    private function getAccessTokenRepo(): AccessTokenRepo
    {
        return new AccessTokenRepo($this->getDmg());
    }
}

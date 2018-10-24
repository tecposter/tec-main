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

    private function getAccessTokenRepo(): AccessTokenRepo
    {
        return new AccessTokenRepo($this->getDmg());
    }
}

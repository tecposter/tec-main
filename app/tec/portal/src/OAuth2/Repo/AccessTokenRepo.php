<?php
namespace Tec\Portal\OAuth2\Repo;

use Gap\Dto\DateTime;
use Tec\Portal\OAuth2\Dto\AccessTokenDto;

class AccessTokenRepo extends RepoBase
{
    private $table = 'open_access_token';

    public function create(AccessTokenDto $accessToken, bool $hasRefresh = false): void
    {
        if (!$accessToken->token) {
            $accessToken->token = base64_encode(random_bytes(24));
        }

        $accessToken->refresh = $hasRefresh ? base64_encode(random_bytes(24)) : '';

        $accessToken->created = new DateTime();
        $accessToken->expired = new DateTime();
        $accessToken->expired->add(new \DateInterval("P1D"));

        //var_dump($accessToken); exit();
        $this->cnn->isb()
            ->insert($this->table)
            ->field(
                'token',
                'appId',
                'userId',
                'refresh',
                'scope',
                'created',
                'expired'
            )->value()
                ->addStr($accessToken->token)
                ->addStr($accessToken->appId)
                ->addStr($accessToken->userId)
                ->addStr($accessToken->refresh)
                ->addStr($accessToken->scope)
                ->addDateTime($accessToken->created)
                ->addDateTime($accessToken->expired)
            ->end()
            ->execute();
    }
}

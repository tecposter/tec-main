<?php
namespace Tec\User\Repo;

use Tec\User\Dto\AccessTokenDto;

class AccessTokenRepo extends RepoBase
{
    const ACCESS_TOKEN_TABLE = 'open_access_token';

    public function create(int $userId, int $appId, \DateInterval $ttl): AccessTokenDto
    {
        if ($userId <= 0) {
            throw new \Exception('userId error format: ' . $userId);
        }
        if ($appId <= 0) {
            throw new \Exception('appId error format: ' . $appId);
        }

        $table = self::ACCESS_TOKEN_TABLE;

        $accessToken = new AccessTokenDto([
            'token' => $this->createToken(),
            'refresh' => $this->createRefresh(),
            'scope' => '',
            'created' => new DateTime(),
            'expired' => (new DateTime())->add($ttl)
        ]);

        $this->cnn->isb()
            ->insert($table)
            ->field('token', 'refresh', 'userId', 'appId', 'scrope', 'created', 'expired')
            ->value()
                ->addStr($accessToken->token)
                ->addStr($accessToken->refresh)
                ->addInt($userId)
                ->addInt($appId)
                ->addStr($accessToken->scope)
                ->addDateTime($accessToken->created)
                ->addDateTime($accessToken->expired)
            ->end()
            ->execute();

        return $accessToken;
    }
}

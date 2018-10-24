<?php
namespace Tec\User\Repo;

use Tec\User\Dto\AccessTokenDto;
use Gap\Dto\DateTime;

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
            'token' => $this->generateToken(),
            'refresh' => $this->generateRefresh(),
            'scope' => '',
            'created' => new DateTime(),
            'expired' => (new DateTime())->add($ttl)
        ]);

        $this->cnn->isb()
            ->insert($table)
            ->field('token', 'refresh', 'userId', 'appId', 'scope', 'created', 'expired')
            ->value()
                ->addStr($accessToken->token->getBin())
                ->addStr($accessToken->refresh->getBin())
                ->addInt($userId)
                ->addInt($appId)
                ->addStr($accessToken->scope)
                ->addDateTime($accessToken->created)
                ->addDateTime($accessToken->expired)
            ->end()
            ->execute();

        return $accessToken;
    }

    public function fetch(string $tokenHex): ?AccessTokenDto
    {
        $token =  hex2bin($tokenHex);
        return $this->cnn->ssb()
            ->select('userId', 'appId', 'token', 'refresh', 'userId', 'appId', 'scope', 'created', 'expired')
            ->from(self::ACCESS_TOKEN_TABLE)->end()
            ->where()
                ->expect('token')->equal()->str($token)
            ->end()
            ->fetch(AccessTokenDto::class);
    }

    private function generateToken(): string
    {
        return n100sBin() . random_bytes(4);
    }

    private function generateRefresh(): string
    {
        return n100sBin() . random_bytes(4);
    }
}

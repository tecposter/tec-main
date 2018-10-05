<?php
namespace Tec\Portal\OAuth2\Repo;

use Tec\Portal\OAuth2\Dto\AppDto;

class AppRepo extends RepoBase
{
    private $table = 'open_app';

    public function fetchByCode(string $code): ?AppDto
    {
        return $this->cnn->ssb()
            ->select(
                'appId',
                'appCode',
                'appSecret',
                'appName',
                'redirectUrl',
                'privilege',
                'scope',
                'created',
                'changed'
            )
            ->from($this->table)->end()
            ->where()
                ->expect('appCode')->equal()->str($code)
            ->end()
            ->fetch(AppDto::class);
    }
}

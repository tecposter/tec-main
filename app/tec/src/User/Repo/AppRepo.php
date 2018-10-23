<?php
namespace Tec\User\Repo;

class AppRepo extends RepoBase
{
    const APP_TABLE = 'open_app';

    public function fetch(string $inAppKey): ?AppDto
    {
        $appKey = trim($inAppKey);
        if (empty($appKey)) {
            throw new \Exception('appKey cannot be empty');
        }

        $table = self::APP_TABLE;

        return $this->cnn->ssb()
            ->select('appId', 'appKey')
            ->from($table)->end()
            ->where()
                ->expect('appKey')->equal()->str($appKey)
            ->end()
            ->fetch(AppDto::class);
    }
}

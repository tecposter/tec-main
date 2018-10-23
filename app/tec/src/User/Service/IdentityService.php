<?php
namespace Tec\User\Service;

use Gap\Dto\DateTime;

use Tec\User\Dto\UserDto;
use Tec\User\Dto\IdentityDto;

use Tec\User\Repo\AppRepo;
use Tec\User\Repo\IdentityRepo;
use Tec\User\Repo\AccessTokenRepo;

/*
 openssl genrsa -out private.pem 2048
 openssl rsa -in private.pem -outform PEM -pubout -out public.pem
 */

class IdentityService extends ServiceBase
{
    public function access(int $userId): ?AccessToken
    {
        /*
        $idToken = $this->strToToken($idTokenStr);
        if (!$idToken) {
            throw new \Exception('idToken error format: ' . $idTokenStr);
        }
        if ($idToken->isExpired()) {
            throw new \Exception('idToken expired');
        }
        if (!$this->verifyToken($idToken)) {
            throw new \Exception('verify token failed');
        }
        $data = $this->fetchData($idToken->getClaim('sub'));
        if (!$data) {
            throw new \Exception('Cannot fetch data by ' . $idToken->getClaim('sub'));
        }
        $userId = $data['userId'] ?? null;
        if (!$userId) {
            throw new \Exception('Cannot find userId in identity data');
        }
         */
        if ($userId <= 0) {
            throw new \Exception('userId error format: ' . $userId);
        }
        $app = $this->getAppRepo()->fetch($this->getAppKey());
        return $this->getAccessTokenRepo()->create($userId, $app->appId, $this->getTtl());
    }

    public function fetchData(string $subject): array
    {
        $subjectArr = explode('|', $subject);
        if ($subjectArr[0] !== $this->getBaseHost()) {
            throw new \Exception('unkown vendor');
        }
        if (!isset($subjectArr[1])) {
            throw new \Exception('subject error format: ' . $subject);
        }
        $code = $subjectArr[1];
        return $this->getIdentityRepo()->fetchData($code);
    }


    public function createIdentity(array $data): IdentityDto
    {
        return $this->getIdentityRepo()->create($data, $this->getTtl());
    }

    private function getAppKey(): string
    {
        return $this->app->getConfig()->config('open')->str('appKey');
    }

    private function getAppRepo(): AppRepo
    {
        return new AppRepo($this->getDmg());
    }

    private function getAccessTokenRepo(): AccessTokenRepo
    {
        return new AccessTokenRepo($this->getDmg());
    }

    private function getIdentityRepo(): IdentityRepo
    {
        return new IdentityRepo($this->getDmg());
    }
}

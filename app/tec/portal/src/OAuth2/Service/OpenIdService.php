<?php
namespace Tec\Portal\OAuth2\Service;

use Lcobucci\JWT\Signer\Keychain;
use Lcobucci\JWT\Signer\Rsa\Sha256;
use Lcobucci\JWT\Parser;

use Tec\Portal\OAuth2\Repo\AppRepo;
use Tec\Portal\OAuth2\Dto\AccessTokenDto;
use Tec\Portal\OAuth2\Repo\AccessTokenRepo;

//use Tec\Portal\OAuth2\Dto\AppDto;

class OpenIdService extends ServiceBase
{
    private $appRepo;
    private $accessTokenRepo;

    public function accessTokenByIdToken(string $idTokenStr, string $appCode): AccessTokenDto
    {
        $idToken = $this->parseIdTokenStr($idTokenStr);
        if (!$this->verifyIdToken($idToken)) {
            return null;
        }
        $appDto = $this->getAppRepo()->fetchByCode($appCode);
        if (!$appDto) {
            return null;
        }

        $sub = $idToken->getClaim('sub');
        if (!$sub) {
            return null;
        }

        list($vendor, $userId) = explode('|', $sub);
        $accessTokenDto = new AccessTokenDto([
            'appId' => $appDto->appId,
            'userId' => $userId,
            'scope' => '',
        ]);

        $this->getAccessTokenRepo()->create($accessTokenDto);
        return $accessTokenDto;
    }

    public function verifyIdToken($idToken): bool
    {
        $publicKey = $this->app->getConfig()->config('openId')->str('publicKey');
        $signer = new Sha256();
        $keychain = new Keychain();
        return $idToken->verify($signer, $keychain->getPublicKey($publicKey));
    }

    public function verifyIdTokenStr(string $idTokenStr): bool
    {
        //$idToken = (new Parser())->parse($idTokenStr);
        $idToken = $this->parseIdTokenStr($idTokenStr);
        return $this->verifyIdToken($idToken);
    }

    public function parseIdTokenStr(string $idTokenStr)
    {
        $idToken = (new Parser())->parse($idTokenStr);
        return $idToken;
    }

    private function getAppRepo(): AppRepo
    {
        if ($this->appRepo) {
            return $this->appRepo;
        }

        $this->appRepo = new AppRepo($this->getDmg());
        return $this->appRepo;
    }

    private function getAccessTokenRepo(): AccessTokenRepo
    {
        if ($this->accessTokenRepo) {
            return $this->accessTokenRepo;
        }
        $this->accessTokenRepo = new AccessTokenRepo($this->getDmg());
        return $this->accessTokenRepo;
    }
}

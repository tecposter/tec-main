<?php
namespace Tec\User\Service;

use Lcobucci\JWT\Builder;
use Lcobucci\JWT\Signer\Keychain;
use Lcobucci\JWT\Signer\Rsa\Sha256;

use Tec\User\Dto\UserDto;
use Gap\Dto\DateTime;

/*
 openssl genrsa -out private.pem 2048
 openssl rsa -in private.pem -outform PEM -pubout -out public.pem
 */

class OpenIdService extends ServiceBase
{
    public function createIdTokenByUser(UserDto $userDto)
    {
        $config = $this->app->getConfig();
        $openIdConfig = $config->config('openId');
        $issuer = $openIdConfig->str('issuer');
        $audience = $config->str('baseHost');
        $subject = 'tecposter|' . $userDto->userId;
        $privateKey = $openIdConfig->str('privateKey');
        $expired = (new DateTime())->add($this->getIdTokenTtl());
        // https://github.com/lcobucci/jwt/blob/3.2/README.md
        $signer = new Sha256();
        $keychain = new Keychain();
        $token = (new Builder())
            ->setIssuer($issuer) // Configures the issuer (iss claim)
            ->setSubject($subject)
            ->setAudience($audience)
            ->setIssuedAt(time()) // Configures the time that the token was issue (iat claim)
            ->setExpiration($expired->getTimestamp()) // Configures the expiration time of the token (exp claim)
            ->sign($signer, $keychain->getPrivateKey($privateKey))
            ->getToken();
        return $token;
    }

    private function getIdTokenTtl(): \DateInterval
    {
        return new \DateInterval('P1M');
    }
}

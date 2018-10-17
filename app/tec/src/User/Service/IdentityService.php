<?php
namespace Tec\User\Service;

use Lcobucci\JWT\Builder;
use Lcobucci\JWT\Token;
use Lcobucci\JWT\Parser;
use Lcobucci\JWT\Signer\Keychain;
use Lcobucci\JWT\Signer\Rsa\Sha256;

use Gap\Dto\DateTime;

use Tec\User\Dto\UserDto;
use Tec\User\Dto\IdentityDto;
use Tec\User\Repo\IdentityRepo;

/*
 openssl genrsa -out private.pem 2048
 openssl rsa -in private.pem -outform PEM -pubout -out public.pem
 */

class IdentityService extends ServiceBase
{
    public function createIdTokenByUser(UserDto $userDto): Token
    {
        $identityDto = $this->createIdentityDto($userDto);

        $config = $this->app->getConfig();
        $identityConfig = $config->config('identity');
        $issuer = $identityConfig->str('issuer');

        $baseHost = $config->str('baseHost');
        $audience = $baseHost;

        $uniqId = bin2hex($identityDto->code);
        $subject = "$baseHost|$uniqId";

        $privateKey = $identityConfig->str('privateKey');
        $issued = new DateTime();
        $expired = (new DateTime())->add($this->getIdTokenTtl());

        // https://github.com/lcobucci/jwt/blob/3.2/README.md
        // https://auth0.com/docs/tokens/id-token
        /*
         * sub:     The unique identifier of the user.
         *          This is guaranteed to be unique per user and will be in the format
         *          (identity provider)|(unique id in the provider), such as github|1234567890.
         */
        $signer = new Sha256();
        $keychain = new Keychain();
        $token = (new Builder())
            ->setIssuer($issuer) // Configures the issuer (iss claim)
            ->setSubject($subject)
            ->setAudience($audience)
            ->setIssuedAt($issued->getTimestamp()) // Configures the time that the token was issue (iat claim)
            ->setExpiration($expired->getTimestamp()) // Configures the expiration time of the token (exp claim)
            ->sign($signer, $keychain->getPrivateKey($privateKey))
            ->getToken();
        return $token;
    }

    public function fetchUserByCode(string $code): ?UserDto
    {
        $identityRepo = new IdentityRepo($this->getDmg());
        $dataStr = $identityRepo->fetchDataByCode($code);
        if (!$dataStr) {
            return null;
        }

        $userDto = new UserDto(json_decode($dataStr, true));
        return $userDto;
    }

    private function createIdentityDto(UserDto $userDto): IdentityDto
    {
        $identityRepo = new IdentityRepo($this->getDmg());
        $identityDto = new IdentityDto([
            'data' => json_encode([
                'userId' => $userDto->userId,
                'code' => $userDto->code,
                'fullname' => $userDto->fullname,
                'phone' => $userDto->phone,
                'email' => $userDto->email
            ]),
            'created' => new DateTime(),
            'expired' => (new DateTime())->add($this->getIdTokenTtl())
        ]);
        $identityRepo->create($identityDto);
        return $identityDto;
    }

    public function strToToken(string $tokenStr): Token
    {
        return (new Parser())->parse($tokenStr);
    }

    public function verifyToken(Token $token): bool
    {
        $publicKey = $this->app->getConfig()->config('identity')->str('publicKey');
        $signer = new Sha256();
        $keychain = new Keychain();
        return $token->verify($signer, $keychain->getPublicKey($publicKey));
    }

    private function getIdTokenTtl(): \DateInterval
    {
        return new \DateInterval('P1M');
    }
}

<?php
namespace Tec\User\Service;

use Lcobucci\JWT\Builder;
use Lcobucci\JWT\Token;
use Lcobucci\JWT\Parser;
use Lcobucci\JWT\Signer\Keychain;
use Lcobucci\JWT\Signer\Rsa\Sha256;

class IdTokenService extends ServiceBase
{
    public function createToken(string $identityCode): Token
    {
        $issuer = $this->getIssuer();
        $baseHost = $this->getBaseHost();
        $audience = $baseHost;
        $subject = "$baseHost|$identityCode";
        $privateKey = $this->getPrivateKey();
        $issued = new DateTime();
        $expired = (new DateTime())->add($this->getTtl());

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

    public function extractIdentityCode(Token $token): string
    {
        $subject = $token->getClaim('sub');
        $subjectArr = explode('|', $subject);
        if ($subjectArr[0] !== $this->getBaseHost()) {
            throw new \Exception('unkown vendor');
        }
        if (!isset($subjectArr[1])) {
            throw new \Exception('subject error format: ' . $subject);
        }
        return $subjectArr[1];
    }

    public function strToToken(string $tokenStr): Token
    {
        return (new Parser())->parse($tokenStr);
    }

    public function verifyToken(Token $token): bool
    {
        $publicKey = $this->getPublicKey();
        $signer = new Sha256();
        $keychain = new Keychain();
        return $token->verify($signer, $keychain->getPublicKey($publicKey));
    }

    private function getIssuer(): string
    {
        $identityConfig = $this->app->getConfig()->config('identity');
        return $identityConfig->str('issuer');
    }

    private function getBaseHost(): string
    {
        return $this->app->getConfig()->str('baseHost');
    }

    private function getPrivateKey(): string
    {
        return $this->app->getConfig()->config('identity')->str('privateKey');
    }

    private function getPublicKey(): string
    {
        return $this->app->getConfig()->config('identity')->str('publicKey');
    }

    private function getTtl(): \DateInterval
    {
        return new \DateInterval('P1M');
    }
}

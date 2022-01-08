<?php declare(strict_types=1);

namespace App\Model\User\Service;

class AccessTokenManager
{
    use JWTTrait {
        JWTTrait::__construct as private __jwtConstruct;
    }

    public function __construct(
        string $privateKeyPath,
        string $publicKeyPath,
        string $passphrase,
    ) {
        self::__jwtConstruct($privateKeyPath, $publicKeyPath, $passphrase);
    }

    public function createToken(string $userId, array $scopes): string
    {
        $now = new \DateTimeImmutable();

        return $this->config->builder()
            ->issuedAt($now)
            ->expiresAt($now->modify('+20 minutes'))
            ->withClaim('user_id', $userId)
            ->withClaim('scope', $scopes)
            ->getToken($this->config->signer(), $this->config->signingKey())
            ->toString();
    }

    public function decode(string $jwt): array
    {
        $parsedToken = $this->parse($jwt);

        return $parsedToken->claims()->all();
    }
}

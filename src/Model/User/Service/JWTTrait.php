<?php declare(strict_types=1);

namespace App\Model\User\Service;

use Lcobucci\JWT\Configuration;
use Lcobucci\JWT\Signer;
use Lcobucci\JWT\Signer\Key\InMemory;
use Lcobucci\JWT\Token;
use Lcobucci\JWT\Validation\Constraint\SignedWith;
use App\Model\User\Exception\JWTDecodeFailureException;

trait JWTTrait
{
    private Configuration $config;
    private string $publicKeyPath;
    private string $passphrase;

    public function __construct(
        string $privateKeyPath,
        string $publicKeyPath,
        string $passphrase,
    ) {
        $this->config = Configuration::forAsymmetricSigner(
            new Signer\Rsa\Sha256(),
            InMemory::file($privateKeyPath, $passphrase),
            InMemory::plainText(''),
        );

        $this->publicKeyPath = $publicKeyPath;
        $this->passphrase = $passphrase;
    }

    private function parse(string $jwt): Token
    {
        try {
            $parsedToken = $this->config->parser()->parse($jwt);
        } catch (\Exception) {
            throw new JWTDecodeFailureException(JWTDecodeFailureException::INVALID_TOKEN, 'Invalid JWT Token.');
        }

        if (!$this->config->validator()->validate($parsedToken, new SignedWith(new Signer\Rsa\Sha256(), InMemory::file($this->publicKeyPath, $this->passphrase)))) {
            throw new JWTDecodeFailureException(JWTDecodeFailureException::UNVERIFIED_TOKEN, 'Invalid JWT Token.');
        }

        if ($parsedToken->isExpired(new \DateTimeImmutable())) {
            throw new JWTDecodeFailureException(JWTDecodeFailureException::EXPIRED_TOKEN, 'Invalid JWT Token.');
        }

        return $parsedToken;
    }
}

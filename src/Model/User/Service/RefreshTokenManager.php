<?php declare(strict_types=1);

namespace App\Model\User\Service;

use Defuse\Crypto\Crypto;
use Doctrine\ORM\EntityManagerInterface;
use Lcobucci\JWT\Configuration;
use Symfony\Component\Uid\Ulid;
use App\Model\User\Entity\RefreshSession;
use App\Model\User\Exception\JWTDecodeFailureException;
use App\Model\User\Repository\RefreshSessionRepository;
use App\Model\User\Repository\UserRepository;

class RefreshTokenManager
{
    use JWTTrait {
        JWTTrait::__construct as private __jwtConstruct;
    }

    private Configuration $config;
    private EntityManagerInterface $entityManager;
    private UserRepository $userRepository;
    private RefreshSessionRepository $refreshSessionRepository;
    private string $encryptionKey;
    private string $publicKeyPath;
    private string $passphrase;

    public function __construct(
        string $privateKeyPath,
        string $publicKeyPath,
        string $passphrase,
        string $encryptionKey,
        EntityManagerInterface $entityManager,
        UserRepository $userRepository,
        RefreshSessionRepository $refreshSessionRepository,
    ) {
        self::__jwtConstruct($privateKeyPath, $publicKeyPath, $passphrase);

        $this->entityManager = $entityManager;
        $this->userRepository = $userRepository;
        $this->refreshSessionRepository = $refreshSessionRepository;
        $this->encryptionKey = $encryptionKey;
    }

    /**
     * @throws \Defuse\Crypto\Exception\EnvironmentIsBrokenException
     */
    public function createToken(string $userId, string $deviceInfo): string
    {
        $tokenId = new Ulid();
        $now = new \DateTimeImmutable();
        $expiresAt = $now->modify('+6 months');

        $user = $this->userRepository->get(Ulid::fromString($userId));

        $sessions = $this->refreshSessionRepository->findByUserIdAndDeviceInfo($deviceInfo, $userId);

        foreach ($sessions as $session) {
            $this->entityManager->remove($session);
        }

        $newSession = new RefreshSession(
            $tokenId,
            $expiresAt,
            $deviceInfo,
            $user,
        );

        $this->refreshSessionRepository->add($newSession);

        $this->entityManager->flush();

        $token = $this->config->builder()
            ->issuedAt($now)
            ->expiresAt($expiresAt)
            ->withClaim('user_id', $userId)
            ->withClaim('refresh_session_id', $tokenId->toRfc4122())
            ->getToken($this->config->signer(), $this->config->signingKey())
            ->toString();

        return Crypto::encryptWithPassword($token, $this->encryptionKey);
    }

    /**
     * @throws JWTDecodeFailureException
     * @throws \Defuse\Crypto\Exception\WrongKeyOrModifiedCiphertextException
     * @throws \Defuse\Crypto\Exception\EnvironmentIsBrokenException
     */
    public function decode(string $refreshToken): array
    {
        $jwt = Crypto::decryptWithPassword($refreshToken, $this->encryptionKey);

        $parsedToken = $this->parse($jwt);

        return $parsedToken->claims()->all();
    }
}

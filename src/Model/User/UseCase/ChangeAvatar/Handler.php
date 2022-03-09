<?php declare(strict_types=1);

namespace App\Model\User\UseCase\ChangeAvatar;

use App\Model\User\Repository\UserRepository;
use App\Model\User\Service\AvatarCropper;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Uid\Uuid;

class Handler
{
    public function __construct(
        private AvatarCropper          $avatarCropper,
        private UserRepository         $userRepository,
        private EntityManagerInterface $entityManager,
        private string                 $publicDir,
        private string                 $uploadDir,
    ) {
    }

    public function handle(Command $command): string
    {
        $user = $this->userRepository->get($command->userId);

        $publicPathPart = '/' . date('Y/m/d');
        $saveDir = $this->uploadDir . $publicPathPart;
        $imageFileName = Uuid::v4()->toRfc4122() . '.jpg';
        $savePath = $saveDir . '/' . $imageFileName;

        if (!file_exists($saveDir)) {
            mkdir($saveDir, 0755, true);
        }

        $this->avatarCropper->crop(
            imagePath: $command->imagePath,
            savePath: $savePath,
            cropWidth: $command->cropWidth,
            cropHeight: $command->cropHeight,
            cropXPosition: $command->cropXPosition,
            cropYPosition: $command->cropYPosition,
        );

        if ($user->getAvatarImage()) {
            @unlink($this->uploadDir . $user->getAvatarImage());
        }

        $user->setAvatarImage($publicPathPart . '/' . $imageFileName);

        $this->entityManager->flush();

        return $this->publicDir . $publicPathPart . '/' . $imageFileName;
    }
}

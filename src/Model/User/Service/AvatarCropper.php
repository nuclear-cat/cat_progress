<?php declare(strict_types=1);

namespace App\Model\User\Service;

class AvatarCropper
{
    /**
     * @throws \Exception
     */
    public function crop(
        string $imagePath,
        string $savePath,
        int $cropWidth,
        int $cropHeight,
        int $cropXPosition,
        int $cropYPosition,
    ): void {
        $imageData = getimagesize($imagePath);

        $image = match ($imageData['mime']) {
            'image/jpeg' => @imagecreatefromjpeg($imagePath),
            'image/png' => @imagecreatefrompng($imagePath),
            'image/gif' => @imagecreatefromgif($imagePath),
            default => throw new \Exception('Unknown image mime.'),
        };

        $newImageWidth = 512;
        $newImageHeight = 512;

        $newImage = imagecreatetruecolor($newImageWidth, $newImageHeight);
        imagecopyresampled(
            $newImage,
            $image,
            0,
            0,
            $cropXPosition,
            $cropYPosition,
            $newImageWidth,
            $newImageHeight,
            $cropWidth,
            $cropHeight,
        );

        $result = imagejpeg($newImage, $savePath);

        if (!$result) {
            throw new \Exception('Can\'t save image.');
        }
    }
}

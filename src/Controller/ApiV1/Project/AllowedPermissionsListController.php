<?php declare(strict_types=1);

namespace App\Controller\ApiV1\Project;

use App\Model\Progress\Entity\Project\Permission;
use App\Security\UserIdentity;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @method UserIdentity getUser()
 */
class AllowedPermissionsListController extends AbstractController
{
    #[Route('/api/v1/project/allowed_permissions', name: 'api.v1.project.allowed_permissions', methods: ['GET'])]
    public function allowedPermissions(): JsonResponse
    {
        return $this->json([
            'success' => true,
            'permissions' => array_map(function (Permission $permission): array {
                return [
                    'title' => $permission->name,
                    'value' => $permission->value,
                ];
            }, Permission::cases()),
        ]);
    }
}

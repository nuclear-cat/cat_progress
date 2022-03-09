<?php declare(strict_types=1);

namespace App\Model\Progress\Entity\Project;

enum Permission: string
{
    case Edit = 'edit';
    case ManageMembers = 'manage_members';
    case ManageTasks = 'manage_tasks';
}

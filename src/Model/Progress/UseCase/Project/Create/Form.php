<?php declare(strict_types=1);

namespace App\Model\Progress\UseCase\Project\Create;

use App\Model\Progress\UseCase\Category\EditFormTrait;
use Symfony\Component\Form\AbstractType;

class Form extends AbstractType
{
    use EditFormTrait;
}

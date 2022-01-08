<?php declare(strict_types=1);

namespace App\Model\Progress\UseCase\Habit\Create;

use App\Model\Progress\UseCase\Habit\EditFormTrait;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class Form extends AbstractType
{
    use EditFormTrait;

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefault('data_class', Command::class);
    }
}

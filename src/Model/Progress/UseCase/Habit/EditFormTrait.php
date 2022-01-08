<?php

namespace App\Model\Progress\UseCase\Habit;

use App\Model\Progress\Entity\Weekday;
use App\Model\Progress\UseCase\Habit\Update\Command;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

trait EditFormTrait
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title', TextType::class, [
                'attr' => ['maxlength' => 255],
                'label' => 'Title',
            ])
            ->add('description', TextareaType::class, [
                'label' => 'Description',
                'required' => false,
            ])
            ->add('totalPoints', IntegerType::class, [
                'label' => 'Points',
                'required' => false,
            ])
            ->add('weekdays', ChoiceType::class, [
                'multiple' => true,
                'expanded' => true,
                'choice_label' => function (Weekday $item) {
                    return $item->name;
                },
                'choices' => Weekday::cases(),
            ])
            ->add('submit', SubmitType::class, [
                'label' => 'Save',
            ]);
    }

}
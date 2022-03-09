<?php declare(strict_types=1);

namespace App\Model\Progress\UseCase\Category;

use App\Model\Progress\UseCase\Category\Update\Command;
use App\Model\Progress\ValueObject\Color;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
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
            ->add('color', ChoiceType::class, [
                'attr' => ['maxlength' => 25],
                'choices' => Color::cases(),
                'choice_label' => function(Color $color) {
                    return ucfirst($color->getName());
                },
                'label' => 'Color',
//                'expanded' => true,
            ])
            ->add('submit', SubmitType::class, [
                'label' => 'Save',
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefault('data_class', Command::class);
    }
}

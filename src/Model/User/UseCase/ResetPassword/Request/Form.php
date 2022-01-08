<?php declare(strict_types=1);

namespace App\Model\User\UseCase\ResetPassword\Request;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class Form extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('email', EmailType::class, [
                'attr' => ['maxlength' => 255, 'placeholder' => 'Enter your email'],
                'label' => 'Enter your user account\'s verified email address and we will send you a password reset link.',
            ])
            ->add('submit', SubmitType::class, [
                'label' => 'Send password reset email'
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefault('data_class', Command::class);
    }
}

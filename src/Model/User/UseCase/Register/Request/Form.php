<?php declare(strict_types=1);

namespace App\Model\User\UseCase\Register\Request;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class Form extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('email', EmailType::class, ['attr' => ['maxlength' => 255]])
            ->add('name', TextType::class, ['attr' => ['maxlength' => 255]])
            ->add('password', PasswordType::class, ['attr' => ['maxlength' => 255]])
            ->add('timezone', HiddenType::class, ['attr' => ['maxlength' => 255]])
            ->add('submit', SubmitType::class, [
                'label' => 'Create account'
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefault('data_class', Command::class);
    }
}

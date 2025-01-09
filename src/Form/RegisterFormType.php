<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\IsTrue;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class RegisterFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('email', EmailType::class, [
              'label' => 'Email',
              'label_attr' => [
                  'for' => 'email',
                  'class' => 'form-label font-normal text-gray-900'
               ],
              'invalid_message' => 'Adresse invalide',
              'attr' => [ 'id' => 'email', 'class' => 'input', 'placeholder' => 'Adresse email', 'required' => 'true' ],
              'constraints' => [ new NotBlank() ]
            ])
            ->add('password', PasswordType::class, [
              'label' => 'Mot de passe',
              'invalid_message' => 'Mot de passe invalide',
              'attr' => [  'placeholder' => 'Mot de passe', 'type' => 'password', 'required' => 'true' ],
              'constraints' => [ new NotBlank(), new Length(['min' => 8]) ]
            ])
            ->add('confirm', PasswordType::class, [
              'label' => 'Confirmez le mot de passe',
              'invalid_message' => 'Confirmation invalide',
              'attr' => [ 'placeholder' => 'Confirmez le mot de passe', 'type' => 'password', 'required' => 'true' ],
              'constraints' => [ new NotBlank(), new Length(['min' => 8]) ]
            ])
            ->add('cgu', CheckboxType::class, [
              'attr' => [ 'class' => 'checkbox checkbox-sm', 'type' => 'checkbox' ],
              'constraints' => [ new IsTrue() ]
            ])
            ->add('submit', SubmitType::class, [
              'label' => 'Inscription',
              'attr' => [ 'class' => 'btn btn-primary flex justify-center grow' ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([]);
    }
}

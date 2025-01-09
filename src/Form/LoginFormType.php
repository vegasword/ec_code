<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class LoginFormType extends AbstractType
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
                   'attr' => [
                       'id' => 'email',
                       'class' => 'input',
                       'placeholder' => 'Adresse email',
                       'required' => 'true'
                   ]
                ])
            ->add('password', PasswordType::class, [
                   'label' => 'Mot de passe',
                   'invalid_message' => 'Mot de passe invalide',
                   'attr' => [ 
                       'placeholder' => 'Mot de passe',
                       'type' => 'password',
                       'required' => 'true'
                   ]
                ])
            ->add('submit', SubmitType::class, [
                    'label' => 'Connexion',
                    'attr' => [ 'class' => 'btn btn-primary flex justify-center grow' ]
                ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([]);
    }
}

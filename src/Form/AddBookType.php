<?php

namespace App\Form;

use App\Entity\Book;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;

class AddBookType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('book', EntityType::class, [
                'class' => Book::class,
                'label' => 'Livre',
                'label_attr' => [ 'for' => 'book', 'class' => 'form-label font-normal text-gray-900' ],
                'choice_label' => 'name',
                'multiple' => false,
                'expanded' => false
            ])
            ->add('description', TextareaType::class, [
                'attr' => [ 'class' => 'textarea', 'name' => 'description', 'placeholder' => "Notez-ici les idées importantes de l'oeuvre.", 'required' => 'true' ],
                'constraints' => [ new NotBlank() ]
            ])
            ->add('rating', ChoiceType::class, [
                'label' => 'Note',
                'label_attr' => [ 'for' => 'book', 'class' => 'form-label font-normal text-gray-900' ],
                'attr' => [  'id' =>  'book', 'class' => 'select', 'name' => 'rating' ],
                'choices' => ['1' => 1, '1.5' => 1.5, '2' => 2, '2.5' => 2.5, '3' => 3, '3.5' => 3.5, '4' => 4, '4.5' => 4.5, '5' => 5 ]
                
            ])
            ->add('is_read', CheckboxType::class, [
                'label' => false,
                'attr' => [ 'class' => 'checkbox checkbox-sm', 'type' => 'checkbox' ],
                'required' => false
            ])
            ->add('submit', SubmitType::class, [
                'label' => 'Enregistrer',
                'attr' => [  'type' => 'submit', 'class' => 'btn btn-primary' ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([]);
    }
}

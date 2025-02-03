<?php

namespace App\Form;

use App\Entity\Contact;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ContactType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('firstName', TextType::class, [
                'label' => 'Prénom',
                'attr' => [
                    'class' => 'ma-classe',
                    'placeholder' => 'Votre prénom'
                ],
                // 'data' => 'abcdef',
                // 'required' => false,
                // 'empty_data' => 'John Doe'
                'row_attr' => [
                    'class' => 'col-md-6', 'id' => '...',
                ] 
            ])
            ->add('lastName', TextType::class, [
                'label' => 'Nom',
                'attr' => [
                    'class' => 'ma-classe',
                    'placeholder' => 'Votre nom'
                ],
                // 'data' => 'abcdef',
                // 'required' => false,
                // 'empty_data' => 'John Doe'
                'row_attr' => [
                    'class' => 'col-md-6', 'id' => '...',
                ] 
            ])
            ->add('email', EmailType::class, [
                'label' => 'Email',
                'attr' => [
                    'placeholder' => 'Votre email'
                ],
                'row_attr' => [
                    'class' => 'col-md-6', 'id' => '...',
                ] 
            ])
            ->add('object', ChoiceType::class, [
                'label' => 'Sélectionner un motif',
                'choices' => [
                    'Je souhaite devenir formateur' => 'Je souhaite devenir formateur',
                    'Je souhaite devenir développeur' => 'Je souhaite devenir développeur',
                    'Je souhaite postuler' => 'Je souhaite postuler'
                ],
                'row_attr' => [
                    'class' => 'col-md-6', 'id' => '...',
                ] 
            ])
            ->add('message', TextareaType::class, [
                'label' => 'Votre message',
                'attr' => [
                    'placeholder' => 'Votre message'
                ]
            ])
            ->add('save', SubmitType::class, [
                'label' => 'Nous contacter'
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Contact::class,
        ]);
    }
}

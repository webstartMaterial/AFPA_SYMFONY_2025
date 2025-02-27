<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\IsTrue;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Regex;

class RegistrationFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('email', EmailType::class, [
                'label' => 'Email',
                'attr' => [
                    'class' => 'ma-classe',
                    'placeholder' => 'Votre email'
                ],
                // 'data' => 'abcdef',
                // 'required' => false,
                // 'empty_data' => 'John Doe'
                'row_attr' => [
                    'class' => 'col-md-6'
                ] 
            ])
            ->add('phoneNumber', TextType::class, [
                'label' => 'Téléphone',
                'attr' => [
                    'class' => 'ma-classe',
                    'placeholder' => 'Votre Téléphone'
                ],
                // 'data' => 'abcdef',
                // 'required' => false,
                // 'empty_data' => 'John Doe'
                'row_attr' => [
                    'class' => 'col-md-6'
                ] ,
                'constraints' => [  
                    new Regex([
                        'pattern' => '/^(?:\+33|0)[1-9](?:\d{2}){4}$/',
                        'message' => 'Veuillez entrer un numéro de téléphone français valide.'
                    ])
                ]
            ])
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
                    'class' => 'col-md-6'
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
                    'class' => 'col-md-6'
                ] 
            ])
            ->add('address', TextType::class, [
                'label' => 'Adresse',
                'attr' => [
                    'class' => 'ma-classe',
                    'placeholder' => 'Votre adresse'
                ],
                // 'data' => 'abcdef',
                // 'required' => false,
                // 'empty_data' => 'John Doe'
                'row_attr' => [
                    'class' => 'col-md-6'
                ] 
            ])
            ->add('city', TextType::class, [
                'label' => 'Ville',
                'attr' => [
                    'class' => 'ma-classe',
                    'placeholder' => 'Votre Ville'
                ],
                // 'data' => 'abcdef',
                // 'required' => false,
                // 'empty_data' => 'John Doe'
                'row_attr' => [
                    'class' => 'col-md-6'
                ] 
            ])
            ->add('postalCode', TextType::class, [
                'label' => 'Code postal',
                'attr' => [
                    'class' => 'ma-classe',
                    'placeholder' => 'Votre Code postal'
                ],
                // 'data' => 'abcdef',
                // 'required' => false,
                // 'empty_data' => 'John Doe'
                'row_attr' => [
                    'class' => 'col-md-6'
                ] 
            ])
            ->add('agreeTerms', CheckboxType::class, [
                'mapped' => false,
                'constraints' => [
                    new IsTrue([
                        'message' => 'You should agree to our terms.',
                    ]),
                ],
            ])
            ->add('plainPassword', PasswordType::class, [
                // instead of being set onto the object directly,
                // this is read and encoded in the controller
                'mapped' => false,
                'attr' => ['autocomplete' => 'new-password'],
                'constraints' => [
                    new NotBlank([
                        'message' => 'Please enter a password',
                    ]),
                    new Length([
                        'min' => 6,
                        'minMessage' => 'Your password should be at least {{ limit }} characters',
                        // max length allowed by Symfony for security reasons
                        'max' => 4096,
                    ]),
                ],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}

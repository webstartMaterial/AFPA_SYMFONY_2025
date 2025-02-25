<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class OrderFilterType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('orderNumber', TextType::class, [
                'required' => false,
                'label' => 'Numéro de commande',
            ])
            ->add('status', ChoiceType::class, [
                'choices' => [
                    'En attente' => 'pending',
                    'Expédiée' => 'shipped',
                    'Livrée' => 'delivered',
                    'Annulée' => 'canceled',
                ],
                'required' => false,
                'placeholder' => 'Sélectionner un statut',
                'label' => 'Statut',
            ])
            ->add('user', TextType::class, [
                'required' => false,
                'label' => 'Utilisateur (email ou ID)',
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([]);
    }
}

<?php

namespace App\Form;

use App\Entity\Commande;use App\Entity\Lunette;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use App\Entity\User;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;

class CommandeType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        // $builder
        //     ->add('quantite')
        //     ->add('Prix')
        //     ->add('date')
        //     ->add('user', EntityType::class, [
        //         'class' => User::class,
        //         'choice_label' => 'username',
        //     ])
        //     ->add('lunette');
        $builder
            ->add('Quantite', IntegerType::class, [
                'label' => 'quantite',
                'attr' => ['min' => 1], // Optionnel : limite pour éviter une quantité négative
            ])
            ->add('Prix', MoneyType::class, [
                'label' => 'Prix',
                'currency' => 'EUR', // Adaptez la devise à vos besoins
            ])
            ->add('date', DateType::class, [
                'label' => 'date',
                'widget' => 'single_text',
                'attr' => ['class' => 'form-control'],
            ])
            ->add('user', EntityType::class, [
                'class' => User::class,
                'choice_label' => 'username', // Propriété affichée pour l'utilisateur
                'label' => 'Utilisateur',
                'placeholder' => 'Sélectionnez un utilisateur',
            ])
            ->add('lunette', EntityType::class, [
                'class' => Lunette::class,
                'choice_label' => 'modele', // Propriété affichée pour les lunettes
                'label' => 'Produit (Lunette)',
                'placeholder' => 'Sélectionnez une lunette',
            ])
            ->add('Phone', IntegerType::class, [
                'label' => 'Phone',
                'attr' => ['min' => 1], // Optionnel : limite pour éviter une quantité négative
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Commande::class,
        ]);
    }


}

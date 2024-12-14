<?php

namespace App\Form;

// use App\Entity\Voiture;
// use Symfony\Component\Form\AbstractType;
// use Symfony\Component\Form\FormBuilderInterface;
// use Symfony\Component\OptionsResolver\OptionsResolver;
// use Symfony\Component\Validator\Constraints\File;
use App\Entity\Voiture;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\File;

class VoitureType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
        ->add('Quantite', NumberType::class, [
            'label' => 'Quantity',
            'attr' => ['placeholder' => 'Enter quantity'],
        ])
        ->add('Prix', NumberType::class, [
            'label' => 'Price',
            'attr' => ['placeholder' => 'Enter price'],
        ])
        ->add('photo', FileType::class, [
            'label' => 'Image du produit',
            'mapped' => false,
            'required' => false,
            'constraints' => [
                new File([
                    'maxSize' => '2M',
                    'mimeTypes' => [
                        'image/jpeg',
                        'image/png',
                    ],
                    'mimeTypesMessage' => 'Veuillez télécharger une image au format JPEG ou PNG',
                ])
            ],
        ])
        ->add('Description', TextType::class, [
            'label' => 'Description',
            'attr' => ['placeholder' => 'Enter Description'],
        ])
        ->add('Modele', TextType::class, [
            'label' => 'Model',
            'attr' => ['placeholder' => 'Enter model name'],
        ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Voiture::class,
        ]);
    }
}

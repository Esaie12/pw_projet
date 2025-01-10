<?php

namespace App\Form;

use App\Entity\Rating;
use App\Entity\User;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class RatingType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('rating', ChoiceType::class, [
                'label' => 'Note (1 à 5)',
                'choices' => [
                    '1 - Très mauvais' => 1,
                    '2 - Mauvais' => 2,
                    '3 - Moyen' => 3,
                    '4 - Bon' => 4,
                    '5 - Excellent' => 5,
                ],
                'expanded' => false, // Menu déroulant (select)
                'multiple' => false, // Une seule option sélectionnable
            ])
            ->add('review', TextareaType::class, [
                'label' => 'Avis',
                'required' => false,
            ])
            ->add('submit', SubmitType::class, [
                'label' => "Envoyer ma note",
                'attr' => [
                    'class' => 'site-button',
                ],
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Rating::class,
        ]);
    }
}

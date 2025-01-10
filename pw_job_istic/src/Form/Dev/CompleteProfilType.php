<?php

namespace App\Form\Dev;

use App\Entity\Developer;
use App\Entity\User;
use App\Entity\Langage;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;

class CompleteProfilType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
        ->add('firstname', TextType::class, [
            'label' => false,
            'attr' => [
                'class' => 'form-control',
                'placeholder' => 'Nom*',
                'required' => true,
            ],
        ])
        ->add('lastname', TextType::class, [
            'label' => false,
            'attr' => [
                'class' => 'form-control',
                'placeholder' => 'Prénoms*',
                'required' => true,
            ],
        ])
        ->add('aboutMe', TextareaType::class, [
            'label' => false,
            'attr' => [
                'class' => 'form-control',
                'placeholder' => 'À propos de vous*',
                'required' => true,
            ],
        ])

        ->add('profession', TextType::class, [
            'label' => false,
            'attr' => [
                'class' => 'form-control',
                'placeholder' => 'Profession*',
                'required' => true,
            ],
        ])

        ->add('localisation', TextType::class, [
            'label' => false,
            'attr' => [
                'class' => 'form-control',
                'placeholder' => 'Localisation*',
                'required' => true,
            ],
        ])
        ->add('salary', NumberType::class, [
            'label' => false,
            'attr' => [
                'class' => 'form-control',
                'placeholder' => 'Salaire minimum*',
                'required' => true,
            ],
        ])
        ->add('niveau_experience', ChoiceType::class, [
            'label' => false,
            'choices' => [
                '0' => 0,
                '1' => 1,
                '2' => 2,
                '3' => 3,
                '4' => 4,
                '5' => 5,
            ],
            'attr' => [
                'class' => 'form-control',
            ],
            'placeholder' => 'Sélectionnez votre niveau d\'expérience*',
        ])
        ->add('langages', EntityType::class, [
            'label' => 'Langages maîtrisés',
            'class' => Langage::class, // Entité des langages
            'choice_label' => 'name', // Affiche le champ `name` pour chaque langage
            'multiple' => true, // Permet la sélection multiple
            'expanded' => false, // Rendu sous forme de <select> multiple
            'attr' => [
                'class' => 'form-control', // Classe CSS pour le style
            ],
        ])
        ->add('avatar', FileType::class, [
            'label' => false,
            'mapped' => false, // Ne pas mapper directement avec l'entité
            'required' => false,
            'attr' => [
                'class' => 'form-control',
                'placeholder' => 'Téléchargez votre avatar*',
            ],
        ])
        ->add('submit', SubmitType::class, [
            'label' => "Enregistrer les modifications",
            'attr' => [
                'class' => 'site-button',
            ],
        ]);

    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Developer::class,
            'method' => 'POST',
        ]);
    }
}

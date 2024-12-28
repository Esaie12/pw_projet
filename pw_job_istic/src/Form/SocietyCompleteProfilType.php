<?php

namespace App\Form;

use App\Entity\Society;
use App\Entity\User;
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
use Symfony\Component\Form\Extension\Core\Type\IntegerType;

class SocietyCompleteProfilType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
        ->add('name', TextType::class, [
            'label' => 'Nom de la société',
            'required' => true,
            'attr' => [
                'class' => 'form-control',
                'placeholder' => 'Nom de la société',
            ],
        ])
        ->add('localisation', TextType::class, [
            'label' => 'Localisation',
            'required' => true,
            'attr' => [
                'class' => 'form-control',
                'placeholder' => 'Localisation',
            ],
        ])
        ->add('siret', TextType::class, [
            'label' => 'SIRET',
            'required' => false,
            'attr' => [
                'class' => 'form-control',
                'placeholder' => 'Numéro de SIRET',
            ],
        ])
        ->add('telephone', TextType::class, [
            'label' => 'Téléphone',
            'required' => true,
            'attr' => [
                'class' => 'form-control',
                'placeholder' => '(251) 1234-456-7890',
            ],
        ])
        /*->add('galleries', TextType::class, [
            'label' => 'Galeries (URLs séparées par des virgules)',
            'required' => false,
            'attr' => [
                'class' => 'form-control',
                'placeholder' => 'URLs séparées par des virgules',
            ],
        ])*/
        ->add('avatar', FileType::class, [
            'label' => false,
            'mapped' => false, // Ne pas mapper directement avec l'entité
            'required' => false,
            'attr' => [
                'class' => 'form-control',
                'placeholder' => 'Téléchargez votre avatar*',
            ],
        ])
        /*->add('avatar', TextType::class, [
            'label' => 'Avatar (URL)',
            'required' => false,
            'attr' => [
                'class' => 'form-control',
                'placeholder' => 'URL de l’avatar',
            ],
        ])*/
        ->add('website', TextType::class, [
            'label' => 'Site web',
            'required' => true,
            'attr' => [
                'class' => 'form-control',
                'placeholder' => 'https://.../',
            ],
        ])
        ->add('creation_annee', IntegerType::class, [
            'label' => 'Année de création',
            'required' => true,
            'attr' => [
                'class' => 'form-control',
                'placeholder' => 'Année de création',
            ],
        ])
        ->add('about', TextareaType::class, [
            'label' => 'À propos',
            'required' => true,
            'attr' => [
                'class' => 'form-control',
                'placeholder' => 'Description de la société',
                'rows' => 3,
            ],
        ])
        ->add('linkedin', TextType::class, [
            'label' => 'Profil LinkedIn',
            'required' => true,
            'attr' => [
                'class' => 'form-control',
                'placeholder' => 'Lien LinkedIn',
            ],
        ])
        ->add('facebook', TextType::class, [
            'label' => 'Page Facebook',
            'required' => true,
            'attr' => [
                'class' => 'form-control',
                'placeholder' => 'Lien Facebook',
            ],
        ])
        ->add('submit', SubmitType::class, [
            'label' => "Enregistrer",
            'attr' => [
                'class' => 'site-button',
            ],
        ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Society::class,
        ]);
    }
}

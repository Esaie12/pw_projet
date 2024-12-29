<?php

namespace App\Form;

use App\Entity\JobPosting;
use App\Entity\JobType;
use App\Entity\Society;
use App\Entity\Langage;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class JobPostingType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title', TextType::class, [
                'label' => 'Titre du poste',
                'required' => true,
                'attr' => [
                    'class' => 'form-control',
                    'placeholder' => "",
                ],
            ])
            ->add('location', TextType::class, [
                'label' => 'Localisation',
                'required' => true,
                'attr' => [
                    'class' => 'form-control',
                    'placeholder' => "",
                ],
            ])
            ->add('technologies', EntityType::class, [
                'label' => 'Technologies recherchées',
                'class' => Langage::class, // Entité cible
                'choice_label' => 'name', // Utilise le champ `name` de l'entité Langage
                'multiple' => true, // Permet la sélection multiple
                'expanded' => false, // Rendu comme un <select> au lieu de cases à cocher
                'attr' => [
                    'class' => 'wt-select-box selectpicker',
                    'data-live-search' => 'true', // Active la recherche dans Bootstrap Select
                    'id' => 'technologies-select',
                ],
            ])
            ->add('experienceLevel', ChoiceType::class, [
                'label' => 'Niveau d\'expérience requis',
                'choices' => [
                    'Débutant' => 'Débutant',
                    'Intermédiaire' => 'Intermédiaire',
                    'Avancé' => 'Avancé',
                ],
                'attr' => [
                    'class' => 'wt-select-box selectpicker',
                    'data-live-search' => 'true',
                    'id' => 'experience-level-select',
                ],
                'placeholder' => 'Sélectionnez un niveau d\'expérience',
            ])
            ->add('salary', MoneyType::class, [
                'label' => 'Salaire proposé',
                'currency' => 'EUR',
                'attr' => [
                    'class' => 'form-control',
                    'placeholder' => "",
                ],
            ])
            ->add('description', TextareaType::class, [
                'label' => 'Description détaillée',
                'attr' => ['rows' => 5],
                'attr' => [
                    'class' => 'form-control',
                    'placeholder' => "",
                ],
            ])
            ->add('publishedAt', DateType::class, [
                'label' => 'Date de publication',
                'widget' => 'single_text',
                'attr' => [
                    'class' => 'form-control datepicker',
                    'placeholder' => "",
                ],
            ])
           /* ->add('expiresAt', DateType::class, [
                'label' => 'Date d\'expiration',
                'widget' => 'single_text',
            ])*/
            ->add('image', FileType::class, [
                'label' => 'Image',
                'mapped' => false, // Ne pas mapper automatiquement à l'entité
                'required' => false,
                'attr' => [
                    'class' => 'form-control',
                    'placeholder' => "",
                ],
            ])
            ->add('pdfFile', FileType::class, [
                'label' => 'Fichier PDF',
                'mapped' => false, // Ne pas mapper automatiquement à l'entité
                'required' => false,
                'attr' => [
                    'class' => 'form-control',
                    'placeholder' => "",
                ],
            ])
            ->add('jobType', EntityType::class, [
                'label' => 'Type de job',
                'class' => JobType::class,
                'choice_label' => 'name', // Affiche le champ `name` de JobType
                'attr' => [
                    'class' => 'form-control',
                    'placeholder' => "",
                ],
            ])
            ->add('submit', SubmitType::class, [
                'label' => "Publier Job",
                'attr' => [
                    'class' => 'site-button',
                ],
            ]);
            /*->add('society', EntityType::class, [
                'label' => 'Société',
                'class' => Society::class,
                'choice_label' => 'name', // Affiche le champ `name` de Society
            ]);*/
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => JobPosting::class,
        ]);
    }
}

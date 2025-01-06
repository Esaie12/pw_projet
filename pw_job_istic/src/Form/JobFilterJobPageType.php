<?php

namespace App\Form;

use App\Entity\JobPosting;
use App\Entity\JobType;
use App\Entity\Langage;
use App\Entity\Society;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

class JobFilterJobPageType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title', TextType::class, [
                'required' => false,
                'label' => 'Titre',
            ])
            ->add('location', TextType::class, [
                'required' => false,
                'label' => 'Localisation',
            ])
            /*->add('jobType', EntityType::class, [
                'class' => JobType::class,
                'choice_label' => 'id',
            ])*/
            ->add('experienceLevel', ChoiceType::class, [
                'required' => false,
                'label' => 'Niveau d\'expérience requis',
                'choices' => [
                    'Débutant' => 'Débutant',
                    'Intermédiaire' => 'Intermédiaire',
                    'Avancé' => 'Avancé',
                ],
                'expanded' => true, // Affiche les choix sous forme de boutons radio
                'multiple' => false, // Assure qu'un seul choix peut être sélectionné
                'attr' => [
                    'class' => 'experience-level-radio-group', // Classe CSS personnalisée si nécessaire
                ],
                'placeholder' => null, // Pas de placeholder pour les boutons radio
            ])
            ->add('technologies', EntityType::class, [
                'class' => Langage::class,
                'choice_label' => 'name',
                'multiple' => true,
                'required' => false,
            ])
            ->add('submit', SubmitType::class, [
                'label' => "Rechercher Job",
                'attr' => [
                    'class' => 'site-button',
                ],
                
            ]);
        
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => null,
            'method' => 'GET', // Soumission via GET
            'csrf_protection' => false, // Désactiver la protection CSRF
        ]);
    }
}

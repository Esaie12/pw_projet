<?php

namespace App\Form;

use App\Entity\Candidat;
use App\Entity\Developer;
use App\Entity\JobPosting;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class CandidatType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
        ->add('availabilityDate', DateType::class, [
            'label' => 'Date de publication',
            'widget' => 'single_text',
            'required' => true,
            'attr' => [
                'class' => 'form-control datepicker',
                'placeholder' => "",
            ],
        ])
        ->add('motivation', TextareaType::class, [
            'label' => 'motivation',
            'attr' => ['rows' => 5],
            'required' => false,
            'attr' => [
                'class' => 'form-control',
                'placeholder' => "",
            ],
        ])
        ->add('submit', SubmitType::class, [
            'label' => "Envoyer ma candidature",
            'attr' => [
                'class' => 'site-button',
            ],
        ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Candidat::class,
        ]);
    }
}

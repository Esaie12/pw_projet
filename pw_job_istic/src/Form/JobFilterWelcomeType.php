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

class JobFilterWelcomeType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title')
            ->add('location', TextType::class, [
                'required' => false,
                'label' => 'Localisation',
            ])
            /*->add('jobType', EntityType::class, [
                'class' => JobType::class,
                'choice_label' => 'id',
            ])*/
            ->add('technologies', EntityType::class, [
                'class' => Langage::class,
                'choice_label' => 'name',
                'multiple' => true,
                'required' => false,
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

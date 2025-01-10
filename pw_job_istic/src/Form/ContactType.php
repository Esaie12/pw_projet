<?php

namespace App\Form;

use App\Entity\Contact;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ContactType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder

        ->add('nom', TextareaType::class, [
            'label' => 'nom',
            'attr' => [
                'class' => 'form-control',
                'placeholder' => "votre Nom",
            ],
        ])
        ->add('email', EmailType::class, [
            'label' => 'email',
            'required' => true,
            'attr' => [
                'class' => 'form-control',
                'placeholder' => "Adresse Mail",
            ],
        ])
        ->add('telephone', TelType::class, [
            'label' => 'téléphone',
            'required' => true,
            'attr' => [
                'class' => 'form-control intl-tel-input',
                'placeholder' => 'Entrez votre numéro de téléphone',
            ],
        ])
        ->add('sujet', TextareaType::class, [
            'label' => 'sujet',
            'attr' => [
                'class' => 'form-control',
                'placeholder' => "votre Sujet",
            ],
        ])
        ->add('message', TextareaType::class, [
            'label' => 'message',
            'attr' => ['rows' => 5],
            'attr' => [
                'class' => 'form-control',
                'placeholder' => "votre message",
            ],
        ])

        ->add('submit', SubmitType::class, [
            'label' => "Envoyer",
            'attr' => [
                'class' => 'site-button',
            ],
        ]);
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Contact::class,
        ]);
    }
}

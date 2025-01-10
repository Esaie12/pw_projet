<?php

namespace App\Form\Dev;


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

class ModifyPassword extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
            $builder
                ->add('password', PasswordType::class, [
                    'label' => false,
                    'mapped' => false,
                    'attr' => [
                        'class' => 'form-control',
                        'placeholder' => 'Ancien mot de passe*',
                        'required' => true,
                    ],
                ])
                ->add('newPassword', PasswordType::class, [
                    'label' => false,
                    'mapped' => false,
                    'attr' => [
                        'class' => 'form-control',
                        'placeholder' => 'Nouveau mot de passe*',
                        'required' => true,
                    ],
                ])
                ->add('confirmPassword', PasswordType::class, [
                    'label' => false,
                    'mapped' => false,
                    'attr' => [
                        'class' => 'form-control',
                        'placeholder' => 'Confirmer le mot de passe*',
                        'required' => true,
                    ],
                ]);
        }
    
        public function configureOptions(OptionsResolver $resolver): void
        {
            $resolver->setDefaults([
                'data_class' => null, // Ne lie pas ces champs à l'entité User
                'method' => 'POST',
            ]);
        }
    }

    


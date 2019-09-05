<?php

namespace App\Form;

use App\Entity\Meet;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class MeetType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('nom', TextType::class, [
                'attr' => ['class' => 'form-control not-dark']
            ])
            ->add('prenom', TextType::class, [
                'attr' => ['class' => 'form-control not-dark']
            ])
            ->add('tel', TextType::class, [
                'attr' => ['class' => 'form-control not-dark']
            ])
            ->add('mail', TextType::class, [
                'attr' => ['class' => 'form-control not-dark']
            ])
            ->add('meetDate', TextType::class, [
                'attr' => ['class' => 'form-control not-dark']
            ])
            ->add('content', TextareaType::class, [
                'attr' => ['class' => 'form-control not-dark']
            ])
            ->add('record', ChoiceType::class, [
                'choices' => [
                    'Oui' => true,
                    'Non' => false,
                ],
                'attr' => [
                    'class' => 'rightmargin-sm'
                ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Meet::class,
        ]);
    }
}

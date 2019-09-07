<?php

namespace App\Form;

use App\Entity\Clients;
use App\Entity\Mutuelle;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AddClientType extends AbstractType
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
            ->add('birthDate', TextType::class, [
                'attr' => ['class' => 'form-control not-dark']
            ])
            ->add('adress', TextType::class, [
                'attr' => ['class' => 'form-control not-dark']
            ])
            ->add('obs', TextType::class, [
                'attr' => ['class' => 'form-control not-dark']
            ])
            ->add('emploi', TextType::class, [
                'attr' => ['class' => 'form-control not-dark']
            ])
            ->add('ppa', ChoiceType::class, [
                'choices' => [
                    'Oui' => true,
                    'Non' => false,
                ],
                'attr' => [
                    'class' => 'rightmargin-sm'
                ]
            ])
            ->add('mutuelle', EntityType::class, [
                'class' => Mutuelle::class,
                'choice_label' => 'nom'
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Clients::class,
        ]);
    }
}

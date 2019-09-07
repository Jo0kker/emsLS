<?php

namespace App\Form;

use App\Entity\Intervention;
use Doctrine\DBAL\Types\IntegerType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AddInterventionType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('typeInter', TextType::class, [
                'attr' => ['class' => 'form-control not-dark']
            ])
            ->add('constatation', TextType::class, [
                'attr' => ['class' => 'form-control not-dark']
            ])
            ->add('soinAppli', TextType::class, [
                'attr' => ['class' => 'form-control not-dark']
            ])
            ->add('soinCover', CheckboxType::class, [
                'label'    => 'Soin couver par la mutuelle',
                'required' => false,
            ])
            ->add('prix', \Symfony\Component\Form\Extension\Core\Type\IntegerType::class, [
                'attr' => ['class' => 'form-control not-dark'],
                'required' => false
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Intervention::class,
        ]);
    }
}

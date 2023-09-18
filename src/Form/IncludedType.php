<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class IncludedType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('overview', TextareaType::class, [
                'required' => false,
                'row_attr' => [
                    'class' => 'input-group mb-3'
                ]
            ])
            ->add('firstAirDate', DateType::class, [
                'required' => false,
                'html5' => true,
                'widget' => 'single_text',
                'row_attr' => [
                    'class' => 'input-group mb-3'
                ]
            ])
            ->add('poster', TextType::class, [
                'required' => false,
                'row_attr' => [
                    'class' => 'input-group mb-3'
                ]
            ])
            ->add('tmdbId')
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            // Configure your form options here
        ]);
    }


}

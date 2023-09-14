<?php

namespace App\Form;

use App\Entity\Serie;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SerieType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, [
                'label' => "Nom de la Série",
                'row_attr' => [
                    'class' => 'input-group mb-3'
                ]
            ])
            ->add('overview', TextareaType::class, [
                'required' => false,
                'row_attr' => [
                    'class' => 'input-group mb-3'
                ]
            ])
            ->add('status', ChoiceType::class, [
                'choices' => [
                    '' => '',
                    'Canceled' => 'Canceled',
                    'Ended' => 'ended',
                    'Returning' => 'returning',
                ],
                'expanded' => false,
                'multiple' => false,
                'row_attr' => [
                    'class' => 'input-group mb-3'
                ]
            ])
            ->add('popularity', TextType::class, [
                'required' => false,
                'row_attr' => [
                    'class' => 'input-group mb-3'
                ]
            ])
            ->add('vote', TextType::class, [
                'required' => false,
                'row_attr' => [
                    'class' => 'input-group mb-3'
                ]
            ])
            ->add('genres', ChoiceType::class, [
                'choices' => [
                    '' => '',
                    'SF' => 'SF',
                    'Comedy' => 'comedy',
                    'Thriller' => 'thriller',
                    'Western' => 'western'
                ],
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
            ->add('backdrop', HiddenType::class, [
                'required' => false
            ])
            ->add('backdrop_file', FileType::class, [
                'mapped' => false,
                'required' => false
            ])
            ->add('poster', TextType::class, [
                'required' => false,
                'row_attr' => [
                    'class' => 'input-group mb-3'
                ]
            ])
            ->add('submit', SubmitType::class)
            ->addEventListener(FormEvents::PRE_SUBMIT, function(FormEvent $event): void {
                $form = $event->getForm();
                $serie = $event->getData();

                if (\in_array($serie['status'], ['ended', 'Canceled'])) {
                    $form->add('lastAirDate', DateType::class, [
                        'required' => false,
                        'html5' => true,
                        'widget' => 'single_text',
                        'row_attr' => [
                            'class' => 'input-group mb-3'
                        ]
                    ]);
                } else {
                    unset($serie['lastAirDate']);
                    $event->setData($serie);
                }
            })
            ->addEventListener(FormEvents::PRE_SET_DATA, [$this, 'onPreSetData'] )
            ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Serie::class,
        ]);
    }

    public function onPreSetData(FormEvent $event): void
    {
        $form = $event->getForm();
        $serie = $event->getData();

        if (\in_array($serie->getStatus(), ['ended', 'Canceled'])) {
            $form->add('lastAirDate', DateType::class, [
                'required' => false,
                'html5' => true,
                'widget' => 'single_text',
                'row_attr' => [
                    'class' => 'input-group mb-3'
                ]
            ]);
        }
    }
}

<?php

namespace App\Form;

use App\Entity\Project;
use App\Entity\Skill;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\UrlType;
use Symfony\UX\Dropzone\Form\DropzoneType;
use Symfony\Component\Validator\Constraints\Count as Count;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ProjectType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('Title', TextType::class, [
                'label' => false,
                'attr' => [
                    'class' => 'form-control mb-4',
                    'placeholder' => 'Title of the project',
                ],
            ])
            ->add('duration',TextType::class, [
                'label' => false,
                'attr' => [
                    'class' => 'form-control mb-4',
                    'placeholder' => 'Duration',
                ],
            ])
            ->add('createdAt', DateTimeType::class, [
                'label' => 'Starting date',
                'input' => 'datetime_immutable',
                'attr' => [
                    'class' => 'form-control mb-4', 
                ],
                'widget' => 'single_text',
            ])
            ->add('description', TextareaType::class, [
                'label' => false,
                'attr' => [
                    'class' => 'form-control mb-4',
                    'placeholder' => 'Description',
                ],
            ])
            ->add('skills', EntityType::class, [
                'placeholder' => 'Skills',
                'class' => Skill::class,
                'choice_label' => 'name',
                'attr' => [
                    'class' => 'mb-4',
                    // 'label-class' => 'form-check-label',
                ],
                'multiple' => true,
                'expanded' => true,
                'by_reference' => false,
                'constraints' => [
                    new Count([
                        'min' => 1, 'minMessage' => 'Please select at least one skill'
                    ]),
                ]
            ])
            ->add('pictureFile', DropzoneType::class, [
                'data_class'=> null,
                'label' => false,
                'attr' => [
                    'placeholder' => 'Drag and drop a file or click to browse',
                ],
            ])
            ->add('video', UrlType::class, [
                'label' => false,
                'attr' => [
                    'class' => 'form-control mt-4',
                    'placeholder' => 'Demo Link',
                ],
            ])
            ->add('github', UrlType::class, [
                'label' => false,
                'attr' => [
                    'class' => 'form-control mt-4',
                    'placeholder' => 'Github Link',
                ],
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Project::class,
        ]);
    }
}

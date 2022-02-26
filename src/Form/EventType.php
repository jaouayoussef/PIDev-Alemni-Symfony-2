<?php

namespace App\Form;

use App\Entity\Event;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\Validator\Constraints\NotBlank;

class EventType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('E_Name')
            ->add('E_PHOTO', FileType::class, [
        // unmapped means that this field is not associated to any entity property
        'mapped' => false,
        // make it optional so you don't have to re-upload the PDF file
        // every time you edit the Product details
        'required' => false,
        // unmapped fields can't define their validation using annotations
        // in the associated entity, so you can use the PHP constraint classes
        'constraints' => [
            new NotBlank(),
            new File([

                'mimeTypes' => [
                    'image/*',

                ],
                'mimeTypesMessage' => 'merci d"ajouter une image',
            ])
        ],
                'label_attr' => [
                    'class' => 'form-control',
                ],
    ])
            ->add('E_NOTE',TextareaType::class, [
                'attr' => array('cols' => '5', 'rows' => '5'),
                'required' => false])
            ->add('E_Place')
            ->add('E_Price')
            ->add('E_TelNumber',TextType::class, array(
                'required' => true,
                'attr' => ['data-mask' => '00000000']
            ))
            ->add('E_Email')
            ->add('E_DateDebut', DateTimeType::class, [
                'placeholder' => 'Select a value',
                'widget' => 'single_text',
            ])
            ->add('E_DateFin', DateTimeType::class, [
                'placeholder' => 'Select a value',
                'widget' => 'single_text',
            ])
            ->add('E_Nbre')
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Event::class,
        ]);
    }
}

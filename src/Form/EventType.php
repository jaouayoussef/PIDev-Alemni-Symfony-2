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

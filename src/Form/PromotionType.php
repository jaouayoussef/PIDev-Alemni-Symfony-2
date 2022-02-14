<?php

namespace App\Form;

use App\Entity\Promotion;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PromotionType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('P_Name')
            ->add('P_Value')
            ->add('P_Domaine',ChoiceType::class, [
        'choices'  => [
            'cuisine' => 'cuisine',
            'informatique' => 'informatique',
        ],])
            ->add('P_DateB', DateType::class, [
                'widget' => 'single_text'

            ])
            ->add('P_DateF', DateType::class, [
                'widget' => 'single_text'
            ])
            ->add('P_Note',TextareaType::class, [
                'attr' => array('cols' => '5', 'rows' => '5'),
                'required' => false]);

    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Promotion::class,
        ]);
    }
}

<?php

namespace App\Form;

use App\Entity\PromoCodeOwner;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PromoCodeOwnerType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('PCD_Name')
            ->add('PCD_FirstName')
            ->add('PCD_Email')
            ->add('PCD_Job')
            ->add('PCD_TelephoneNumber')
            ->add('PCD_Gender')
            ->add('PCD_City')
            ->add('PCD_Note')
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => PromoCodeOwner::class,
        ]);
    }
}

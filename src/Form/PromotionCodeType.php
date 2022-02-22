<?php

namespace App\Form;

use App\Entity\PromotionCode;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PromotionCodeType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('PC_Code')
            ->add('PC_Value')
            ->add('PC_ExpirationCode')
            ->add('PC_Note')
            ->add('CP_PCD')
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => PromotionCode::class,
        ]);
    }
}

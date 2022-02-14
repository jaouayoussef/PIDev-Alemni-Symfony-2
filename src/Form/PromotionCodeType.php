<?php

namespace App\Form;

use App\Entity\PromoCodeOwner;
use App\Entity\PromotionCode;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PromotionCodeType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('CP_PCD',EntityType::class,[
                'class'=> PromoCodeOwner::class,
                'choice_label'=> 'PCD_Email',
                'choice_value'=> 'id',
                'placeholder' => 'choisir le propriétaire...',
                'multiple'=>false,
                'expanded'=>false, ])
            ->add('PC_Code')
            ->add('PC_Value')
            ->add('PC_ExpirationCode', DateType::class, [
                'widget' => 'single_text'
            ])
            ->add('PC_Note' ,TextareaType::class, [
                'attr' => array('cols' => '5', 'rows' => '5'),
              'required' => false]);
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => PromotionCode::class,
        ]);
    }
}

<?php

namespace App\Form;

use App\Entity\Seance;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TimeType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SeanceType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nomSeance')
            ->add('descriptionSeance')
            ->add('dateSeance', DateType::class, [
                'widget' => 'single_text'
            ])
            ->add('heureDeb', TimeType::class, [
                'widget' => 'single_text'
            ])
            ->add('heureFin', TimeType::class, [
                'widget' => 'single_text'
            ])
            #->add('formation')
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Seance::class,
        ]);
    }
}

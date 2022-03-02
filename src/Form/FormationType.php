<?php

namespace App\Form;

use App\Entity\Domaine;
use App\Entity\Formation;
use http\Url;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\Validator\Constraints\NotBlank;

class FormationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nomFormation')
            ->add('descriptionFormation',TextareaType::class, [
                'attr' => array('cols' => '5', 'rows' => '5')])
            ->add('formateur')
            ->add('lien')
            ->add('prixFormation')
            ->add('nbPlaces')

            ->add('domaine',EntityType::class,[
                'class'=> Domaine::class,
                'choice_label'=>'nomDomaine',
                'choice_value'=>'id',
                'multiple'=>false,
                'expanded'=>false, ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Formation::class,
        ]);
    }
}

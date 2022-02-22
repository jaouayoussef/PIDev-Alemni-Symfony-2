<?php

namespace App\Form;

use App\Entity\Domaine;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\Validator\Constraints\NotBlank;

class DomaineType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nomDomaine')
            ->add('descriptionDomaine',TextareaType::class, [
                'attr' => array('cols' => '5', 'rows' => '5')])
            ->add('imageDomaine', FileType::class, [


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
            ])
            #->add('promotions')
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Domaine::class,
        ]);
    }
}

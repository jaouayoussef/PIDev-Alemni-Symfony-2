<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\Validator\Constraints\Length;

class RegistrationFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder ->add('first_name',TextType::class,[
            'label'=>false,
            'required'=>false,
            'constraints' => array(
                new Length(array('min'=>4))

            )
        ])
            ->add('last_name',TextType::class,[
                'label'=>false,
                'required'=>false,
            ])
            ->add('picture', FileType::class,[
                'label'=>false,
                'required' => true,
                'mapped' => false,
                'constraints' => [
                    new File([
                        'mimeTypes' => [
                            'image/*',
                        ],
                        'mimeTypesMessage' => 'Please upload a valid picture type',
                    ])
                ]
            ])
            ->add('gender',ChoiceType::class, array(
                'label'=> false,
                'required'=>false,
                'choices' => array(
                    'Male'=>'Male',
                    'Female'=>'Female'
                )
            ))
            ->add('email',EmailType::class,array(
                'label'=>false,
                'required'=>false,
            ))
            ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}

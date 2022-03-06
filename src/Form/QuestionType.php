<?php

namespace App\Form;

use App\Entity\Question;
use phpDocumentor\Reflection\Types\Integer;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

class QuestionType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('libelle')
            ->add('reponse1')
            ->add('reponse2')
            ->add('reponse3')
            ->add('reponse4')
            ->add('repcorrect', ChoiceType::class, [
                'choices' => [
                    'réponse 1' => 1,
                    'réponse 2' => 2,
                    'réponse 3' => 3,
                    'réponse 4' => 4,
                    ]])

        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Question::class,
        ]);
    }
}

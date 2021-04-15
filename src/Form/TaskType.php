<?php

namespace App\Form;

use App\Entity\Task;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class TaskType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('Titre')
            ->add('EstValide')
            ->add('Date')
            ->add('Notes')
            ->add('Enregistrer',SubmitType::class,[
                'attr'=>[
                    'class'=>'btn-task'
                ]

            ]);
            
        
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Task::class,
        ]);
    }

    public function getBlockPrefix()
    {
        return 'newTask';
    }

}

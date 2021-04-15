<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;

class ResetEmailType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder

        ->add('email', RepeatedType::class, [
            'type' => EmailType::class,
            'invalid_message' => 'Vos adresses doivent être les mêmes',
            'options' => ['attr' => ['class' => 'email-field']],
            'required' => true,
            'first_options' => ['label' => 'Nouvelle adresse e-mail'],
            'second_options' => ['label' => "Confirmez l'adresse e-mail"],
        ])
            /*
        ->add('Modifiez', SubmitType::class, array(

            'attr' => array(

                'class' => 'btn btn-primary btn-block'

            )

        ))
            */

        ;

    }




    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}

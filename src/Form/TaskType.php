<?php

namespace App\Form;

use App\Entity\Liste;
use App\Entity\Task;
use App\Repository\ListeRepository;
use App\Repository\TaskRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
/**
 * @ORM\Entity(repositoryClass="AppBundle\Repository\ListeRepository")

 */
use Doctrine\ORM\EntityRepository;


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

            ])
            ->add("liste", EntityType::class,[
                'class' => Liste::class,
                'query_builder' => function (ListeRepository $er) {
                    return $er->createQueryBuilder("i")
                        ->orderBy("i.titre");
                },

                'choice_label' => 'titre',
            ]);
    }

    public function createQueryBuilder($alias, $indexBy = null)
    {
        return $this->_em->createQueryBuilder()
            ->select($alias)
            ->from($this->_entityName, $alias, $indexBy);
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

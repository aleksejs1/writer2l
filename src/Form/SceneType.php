<?php

namespace App\Form;

use App\Entity\Character;
use App\Entity\Project;
use App\Entity\Scene;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SceneType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title')
            ->add('content', TextareaType::class, [
                'required' => false,
                'attr' => [
                    'rows' => 25,
                ]
            ])
            ->add('description', TextareaType::class, [
                'required' => false,
                'attr' => [
                    'rows' => 10,
                ]
            ])
            ->add('viewpoint', EntityType::class, [
                'placeholder' => 'None',
                'required' => false,
                'class' => Character::class,
                'choice_label' => 'shortname',
                'query_builder' => function (EntityRepository $entityRepository) use ($options) {
                    return $entityRepository
                        ->createQueryBuilder('c')
                        ->where('c.project = :project')
                        ->setParameter('project', $options['project'])
                        ;
                },
            ])
            ->add('status', ChoiceType::class, [
                'choices' => array_flip(Scene::STATUS_TITLES),
            ])
            ->add('goalType', ChoiceType::class, [
                'choices' => array_flip(Scene::GOAL_TITLES),
            ])
            ->add('goal')
            ->add('conflict')
            ->add('outcome')
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Scene::class,
        ]);
        $resolver->setRequired('project');
        $resolver->setAllowedTypes('project', Project::class);
    }
}

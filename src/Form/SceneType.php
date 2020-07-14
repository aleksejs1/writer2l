<?php

namespace App\Form;

use App\Entity\Character;
use App\Entity\Scene;
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
            ])
            ->add('status', ChoiceType::class, [
                'choices' => array_flip(Scene::STATUS_TITLES),
            ])
            ->add('note', TextareaType::class, [
                'required' => false,
                'attr' => [
                    'rows' => 15,
                ]
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
    }
}

<?php

namespace App\Form;

use App\Entity\Game;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class GameType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('0',    SubmitType::class,['label'=>' '])
            ->add('1',     SubmitType::class,['label'=>' '])
            ->add('2',   SubmitType::class,['label'=>' '])
            ->add('3',    SubmitType::class,['label'=>' '])
            ->add('4',        SubmitType::class,['label'=>' '])
            ->add('5',   SubmitType::class,['label'=>' '])
            ->add('6',    SubmitType::class,['label'=>' '])
            ->add('7',     SubmitType::class,['label'=>' '])
            ->add('8',   SubmitType::class,['label'=>' '])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Game::class,
        ]);
    }
}

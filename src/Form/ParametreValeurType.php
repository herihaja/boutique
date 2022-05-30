<?php

namespace App\Form;

use App\Entity\ParametreValeur;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ParametreValeurType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('codeValeur')
            ->add('valeur')
            ->add('valeur2')
            ->add('description')
            ->add('parametre')
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => ParametreValeur::class,
        ]);
    }
}

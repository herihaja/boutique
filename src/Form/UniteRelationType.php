<?php

namespace App\Form;

use App\Entity\UniteRelation;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use App\Entity\ParametreValeur;

class UniteRelationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('multiple')
            ->add('produit')
            ->add('unite1', EntityType::class, [
                'class' => ParametreValeur::class,
                'choice_label' => function (ParametreValeur $user) {
                    return $user->getValeur();
                },
                'choices' => $builder->getData()->getProduit()->getUnites(),
                'multiple' => false,
                'attr' => ['class' => 'form-control']
            ])
            ->add('unite2', EntityType::class, [
                'class' => ParametreValeur::class,
                'choice_label' => function (ParametreValeur $user) {
                    return $user->getValeur();
                },
                'choices' => $builder->getData()->getProduit()->getUnites(),
                'multiple' => false,
                'attr' => ['class' => 'form-control']
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => UniteRelation::class,
        ]);
    }
}

<?php

namespace App\Form;

use App\Entity\Prix;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use App\Entity\ParametreValeur;

class PrixType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            //->add('date_ajout')
            ->add('valeur')
            ->add('prixAchat')
            ->add('produit')
            ->add('unite', EntityType::class, [
                'class' => ParametreValeur::class,
                'choice_label' => function (ParametreValeur $user) {
                    return $user->getValeur();
                },
                'choices' => $builder->getData()->getProduit()->getUnites(),
                'multiple' => false,
                'attr' => ['class' => 'form-control']
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Prix::class,
        ]);
    }
}

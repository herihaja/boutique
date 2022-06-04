<?php

namespace App\Form;

use App\Entity\Produit;
use App\Entity\ParametreValeur;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\FileType;

class CaisseType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nom')
            ->add('description')
            ->add('image', FileType::class, ["data_class" => null, 'required' => false])
            ->add('categorie', EntityType::class, [
                'class' => ParametreValeur::class,
                'choice_label' => function (ParametreValeur $user) {
                    return $user->getValeur();
                },
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('u')
                        ->where('u.parametre = 1')
                        ->orderBy('u.valeur', 'ASC');
                },
                'multiple' => false,
                'attr' => ['class' => 'form-control']
            ])
            ->add('type', EntityType::class, [
                'class' => ParametreValeur::class,
                'choice_label' => function (ParametreValeur $user) {
                    return $user->getValeur();
                },
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('u')
                        ->where('u.parametre = 2')
                        ->orderBy('u.valeur', 'ASC');
                },
                'multiple' => false,
                'attr' => ['class' => 'form-control']
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([]);
    }
}

<?php

namespace App\Form;

use App\Entity\Personne;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints\Regex;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;

class PersonneType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('nom', TextType::class, ['attr' => ['class' => 'form-control']])
            ->add('prenom', TextType::class, ['attr' => ['class' => 'form-control']])
            ->add('date_naissance', DateType::class, ['attr' => ['class' => 'form-control'], 'widget' =>  'single_text'])
            ->add('cin', IntegerType::class, ['attr' => ['class' => 'form-control'], 'required' => false,
                                              'constraints' => [
                                                  new Regex(array('pattern'=>'/^[0-9]{12}$/', 'message'=>'Valeur invalide, CIN doit être 12 chiffres numérique.'))
                                               ],
                                             ])
            ->add('date_cin', DateType::class, ['attr' => ['class' => 'form-control'], 'widget' =>  'single_text'])
            ->add('ville_cin', TextType::class, ['attr' => ['class' => 'form-control'], 'required' => false])
            ->add('date_duplicata_cin', DateType::class, ['attr' => ['class' => 'form-control'], 'required' => false, 'widget' =>  'single_text'])
            ->add('ville_duplicata_cin', TextType::class, ['attr' => ['class' => 'form-control'], 'required' => false])
            ->add('tel_1', TextType::class, ['attr' => ['class' => 'form-control'],
                                                 'constraints' => [
                                                          new Regex(array('pattern'=>'/^(0|\+261)[0-9]{9}$/', 'message'=>'Valeur invalide.'))
                                                     ],

                        ])
            ->add('tel_2', TextType::class, ['attr' => ['class' => 'form-control'],'required' => false,
                                                 'constraints' => [
                                                          new Regex(array('pattern'=>'/^(0|\+261)[0-9]{9}$/', 'message'=>'Valeur invalide.'))
                                                     ],

                        ])
            ->add('adresse', TextType::class, ['attr' => ['class' => 'form-control']])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Personne::class,
            'constraints' => new UniqueEntity(array('fields' => array('cin'), 'message' => 'Cette valeur est déjà utilisée.')),
        ]);
    }
}

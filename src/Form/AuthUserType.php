<?php

namespace App\Form;

use App\Entity\AuthUser;
use App\Form\Personne;
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

class AuthUserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('password', HiddenType::class)
            ->add(
                'lastLogin',
                DateTimeType::class,
                [
                    'attr' => ['class' => 'form-control'],
                    'empty_data' =>  ''
                ]
            )
            ->add('isSuperuser')
            ->add('username', TextType::class, ['attr' => ['class' => 'form-control']]) //, HiddenType::class)
            ->add('isActive')
            ->add(
                'dateJoined',
                DateTimeType::class,
                [
                    'attr' => ['class' => 'form-control', 'type' => 'hidden'],
                    'empty_data' =>  '', 'widget' => 'single_text'
                ]
            )
            ->add('avatar', FileType::class, ["data_class" => null, 'required' => false])
            ->add('groups')
            ->add('permissions')
            ->add('personne', PersonneType::class);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => AuthUser::class,
            'constraints' => new UniqueEntity(array('fields' => array('email', 'username'))),
            //'required' => array('lastName', 'lastName', 'email'),
        ]);
    }
}

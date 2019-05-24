<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Length;


class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('email')
            ->add('roles', ChoiceType::class, [
                'choices'  => [
                    'User' => "ROLE_USER",
                    'Author' => "ROLE_AUTHOR",
                    'Administrator' => "ROLE_ADMIN",
                ],
                "expanded" => true,
                "multiple" => true
            ])
            ->add('plainPassword', RepeatedType::class, [
                "mapped" => false,
                'type' => PasswordType::class,
                'invalid_message' => 'The password fields must match.',
                'first_options'  => ['label' => 'Password'],
                'second_options' => ['label' => 'Repeat Password'],
                'constraints' => [
                    new NotBlank([
                        'groups' => ['create']
                    ]),
                    new Length([
                        'min' => 6,
                        'max' => 25,
                        'groups' => ['create', 'Default']
                    ]),
                ],
            ])
            ->add('lastName')
            ->add('firstName')
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}

<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\IsTrue;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Regex;

class RegistrationFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('email')
            ->add('username', TextType::class, ['constraints' => [
                new NotBlank([
                    'message' => 'Your username can\'t be empty.'
                ]),
                new Length([
                    'min' => 2,
                    'max' => 40,
                    'minMessage' => 'Your username should be at least {{ limit }} characters',
                    'maxMessage' => 'Your username is limited to {{ limit }} characters.'
                ]),
                new Regex([
                    'pattern' => '/^[a-z0-9]+(?:_[a-z0-9]+)*$/',
                    'message' => 'Username should only contain lowercase letter, digits and no consecutive underscores.'
                ])
            ]])
            ->add('displayedName', TextType::class, ['constraints' =>[
                new NotBlank([
                    'message' => 'Your username can\'t be empty.'
                ]),
                new Length([
                    'min' => 2,
                    'max' => 40,
                    'minMessage' => 'Your name should be at least {{ limit }} characters',
                    'maxMessage' => 'Your name is limited to {{ limit }} characters.'
                ]),
            ]])
            ->add('agreeTerms', CheckboxType::class, [
                'mapped' => false,
                'constraints' => [
                    new IsTrue([
                        'message' => 'You should agree to our terms.',
                    ]),
                ],
            ])
            ->add('plainPassword', PasswordType::class, [
                // instead of being set onto the object directly,
                // this is read and encoded in the controller
                'mapped' => false,
                'attr' => ['autocomplete' => 'new-password'],
                'constraints' => [
                    new NotBlank([
                        'message' => 'Please enter a password',
                    ]),
                    new Length([
                        'min' => 1,
                        'minMessage' => 'Your password should be at least {{ limit }} characters',
                        // max length allowed by Symfony for security reasons
                        'max' => 255,
                    ]),
                ],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}

<?php

namespace App\Form;

use App\Entity\Group;
use App\Entity\Transaction;
use App\Entity\User;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class TransactionType extends AbstractType
{
    private $security;

    public function __construct(Security $security)
    {
        $this->security = $security;
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        /**
        * @var User
        */
        $user = $this->security->getUser();

        $builder
            ->add('amount')
            ->add('receiver', EntityType::class, [
                'class' => User::class,
                'choices' => $user->getContact(),
                'choice_label' => 'username',
            ])
            ->add('related_group', EntityType::class, [
                'class' => Group::class,
                'choice_label' => 'name',
                'choices' => $user->getGroups(),
                'multiple' => true,
                'required' => false
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Transaction::class,
        ]);
    }
}

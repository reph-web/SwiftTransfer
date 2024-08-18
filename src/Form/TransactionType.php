<?php

namespace App\Form;

use App\Entity\Group;
use App\Entity\Transaction;
use App\Entity\User;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\GreaterThan;
use Symfony\Component\Validator\Constraints\LessThanOrEqual;

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
        ->add('amount', NumberType::class, [
            'label' => 'Amount',
            'constraints' => [
                new NotBlank(),
                new GreaterThan(['value' => 0, 'message' => 'Amount must be greater than zero']),
                new LessThanOrEqual(['value'=> $user->getBalance(), 'message'=> 'You are trying to send more than than you have']),
            ],
            ])
            ->add('receiver', EntityType::class, [
                'class' => User::class,
                'choices' => $user->getContact(),
                'choice_label' => 'username',
            ])
            ->add('related_group', EntityType::class, [
                'class' => Group::class,
                'choice_label' => 'name',
                'choices' => $user->getGroups(),
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

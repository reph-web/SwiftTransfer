<?php

namespace App\Controller\Admin;

use App\Entity\User;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\{AssociationField, TextField, EmailField, CollectionField};


class UserCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return User::class;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id')
            ->setFormTypeOption('disabled', 'disabled'),
            TextField::new('username'),
            EmailField::new('email'),
            TextField::new('displayedName'),
            CollectionField::new('roles'),
            AssociationField::new('groups')
            ->setCrudController(GroupCrudController::class)
            ->formatValue(function ($value, $entity) {
                return implode(', ', $entity->getGroups()->map(fn($g) => $g->getName())->toArray());
            })
            ->setFormTypeOptions([
                'by_reference' => false,
                'choice_label' => 'name',
            ]),
            AssociationField::new('contact')
                ->setCrudController(UserCrudController::class)
                ->formatValue(function ($value, $entity) {
                    return implode(', ', $entity->getContact()->map(fn($u) => $u->getUsername())->toArray());
                })
                ->setFormTypeOptions([
                    'by_reference' => false,
                    'choice_label' => 'username',
                ]),
        ];
    }

    /*
    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id'),
            TextField::new('title'),
            TextEditorField::new('description'),
        ];
    }
    */
}

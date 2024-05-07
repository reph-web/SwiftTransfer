<?php

namespace App\Controller\Admin;

use App\Entity\Group;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class GroupCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Group::class;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id')
            ->setFormTypeOption('disabled', 'disabled'),
            TextField::new('name'),
            TextField::new('description'),
            AssociationField::new('owner')
            ->setLabel('Owner'),
            DateTimeField::new('created_at', 'Created At')
            ->setFormat('Y-MM-dd HH:mm:ss')
            ->setTimezone('Europe/Paris'),
            AssociationField::new('members')
            ->setCrudController(UserCrudController::class)
            ->formatValue(function ($value, $entity) {
                return implode(', ', $entity->getMembers()->map(fn($u) => $u->getUsername())->toArray());
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

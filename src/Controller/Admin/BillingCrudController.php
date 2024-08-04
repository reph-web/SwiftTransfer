<?php

namespace App\Controller\Admin;

use App\Entity\Billing;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\NumberField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class BillingCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Billing::class;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id')
            ->setFormTypeOption('disabled', 'disabled'),
            AssociationField::new('user'),
            NumberField::new('amount'),
            DateTimeField::new('timestamp', 'Timestamp')
            ->setFormat('Y-MM-dd HH:mm:ss')
            ->setTimezone('Europe/Paris'),
            TextField::new('id_stripe')
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

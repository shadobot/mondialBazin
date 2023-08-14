<?php

namespace App\Controller\Admin;

use App\Entity\Product;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextareaField;
use EasyCorp\Bundle\EasyAdminBundle\Field\MoneyField;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\SlugField;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;

class ProductCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Product::class;
    }

    
    public function configureFields(string $pageName): iterable
    {

       yield TextField::new(propertyName: 'name');
       yield TextField::new(propertyName: 'name');
       yield SlugField::new(propertyName: 'slug') -> setTargetFieldName(fieldName: 'name');
       yield ImageField::new(propertyName: 'illustration') 
                -> setBasePath('uploads') 
                -> setUploadDir('public/uploads/')
                -> setUploadedFileNamePattern('[randomhash].[extension]')
                -> setRequired(false);
       yield TextField::new(propertyName: 'subtitle');
       yield TextareaField::new(propertyName: 'description');
       yield BooleanField::new('isBest');
       yield MoneyField::new(propertyName: 'price') -> setCurrency('EUR');   
       yield AssociationField::new(propertyName: 'category');
    }
    
}

<?php

namespace App\Controller\Admin;

use App\Entity\Project;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;

class ProjectCrudController extends AbstractCrudController
{   
    public static function getEntityFqcn(): string
    {
        return Project::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setEntityLabelInPlural('Projects')
            ->setEntityLabelInSingular('Project')
            ->setDateFormat('Y-m-d')
            ->setPaginatorPageSize(10)
        ;
    }
    
    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id')
                ->hideOnForm(),
            TextField::new('title'),
            TextField::new('duration'),
            TextEditorField::new('description')
                ->hideOnIndex(),
            DateTimeField::new('createdAt')
                ->setFormTypeOption('disabled', 'disabled'),
            TextField::new('github')
                ->hideOnIndex(),
            ImageField::new('picture')
                ->setUploadDir('public/uploads/pictures')
                ->setFormTypeOptions(['required' => false])
                ->hideOnIndex(),
            TextField::new('video')
                ->hideOnIndex(),
        ];
    }
}
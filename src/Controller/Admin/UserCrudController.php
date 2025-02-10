<?php

namespace App\Controller\Admin;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ChoiceField;
use EasyCorp\Bundle\EasyAdminBundle\Field\EmailField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserCrudController extends AbstractCrudController
{
    private UserPasswordHasherInterface $passwordHasher;

    public function __construct(UserPasswordHasherInterface $passwordHasher)
    {
        $this->passwordHasher = $passwordHasher;
    }

    public static function getEntityFqcn(): string
    {
        return User::class;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id', 'Identifiant')->hideOnForm(),
            EmailField::new('email', 'Email'),
            TextField::new('plainPassword', 'Nouveau mot de passe')
                ->setFormType(PasswordType::class)
                ->hideOnIndex()
                ->setRequired(false),
            BooleanField::new('isVerified', 'Email vérifiée'),
            ChoiceField::new('roles', 'Rôles')
                ->setChoices([
                    'Utilisateur' => 'ROLE_USER',
                    'Administrateur' => 'ROLE_ADMIN',
                    'Fournisseur' => 'ROLE_FOURNISSEUR',
                ])
                ->allowMultipleChoices()
        ];
    }

    public function updateEntity(EntityManagerInterface $entityManager, $entityInstance): void
    {

        // Si un nouveau mot de passe est saisi, on le hache et on l'enregistre
        if ($entityInstance->getPlainPassword()) {
            $hashedPassword = $this->passwordHasher->hashPassword($entityInstance, $entityInstance->getPlainPassword());
            $entityInstance->setPassword($hashedPassword);
        } else {
            // Charger l'ancien mot de passe si aucun nouveau n'est fourni
            $originalUser = $entityManager->getRepository(User::class)->find($entityInstance->getId());
            $entityInstance->setPassword($originalUser->getPassword());
        }

        // Appeler la méthode parente pour sauvegarder l'entité
        parent::updateEntity($entityManager, $entityInstance);
    }
}

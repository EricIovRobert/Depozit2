<?php

namespace App\Controller;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;

class RegistrationController extends AbstractController
{
    #[Route('/register', name: 'app_register')]
    public function index(UserPasswordHasherInterface $passwordHasher, EntityManagerInterface $entityManager): Response
    {
        // Create a new user object
        $user = new User();
        $user->setEmail('admin@gmail.com');

        // Define the plaintext password
        $plaintextPassword = 'parola';

        // Hash the password based on the security.yaml config for the User class
        $hashedPassword = $passwordHasher->hashPassword(
            $user,
            $plaintextPassword
        );

        // Set the hashed password to the user entity
        $user->setPassword($hashedPassword);

        // Optionally, set user roles
        $user->setRoles(['ROLE_USER']);

        // Persist the user entity to the database
        $entityManager->persist($user);
        $entityManager->flush();

        // Return a response indicating success
        return new Response('User admin@gmail.com successfully created!');
    }
}

<?php

namespace App\Controller;

// ...

use App\Entity\User;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;

class RegistrationController extends AbstractController
{


    #[Route('/api/register', name: 'app_register', methods: ['POST'])]
    public function register(Request $request, UserPasswordHasherInterface $passwordHasher, UserRepository $userRepository): JsonResponse
    {

        // Get the JSON data from the request body
        $data = json_decode($request->getContent(), true);

        // Check if the required fields are present in the request
        if (!isset($data['email']) || !isset($data['password'])) {
            return new Response('Email and password are required', Response::HTTP_BAD_REQUEST);
        }

        $user = new User();
        $plaintextPassword = $data['password'];

        // hash the password (based on the security.yaml config for the $user class)
        $hashedPassword = $passwordHasher->hashPassword(
            $user,
            $plaintextPassword
        );
        $user->setPassword($hashedPassword);
        $user->setEmail($data['email']);

        // i want code for save user
       $newUser= $userRepository->saveUser($user);
       return $this->json($newUser);
    }

}

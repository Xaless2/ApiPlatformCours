<?php

namespace App\Controller;

use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Core\User\UserInterface;

class UserController extends AbstractController
{
    #[Route('/register', name: 'user_register', methods: ['POST'])]
    public function register(Request $request, UserPasswordEncoderInterface $passwordEncoder): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        $user = new User();
        $user->setUsername($data['username']);

        $encodedPassword = $passwordEncoder->encodePassword($user, $data['password']);
        $user->setPassword($encodedPassword);

        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($user);
        $entityManager->flush();

        return new JsonResponse(['message' => 'User registered'], JsonResponse::HTTP_CREATED);
    }

    #[Route('/login', name: 'user_login', methods: ['POST'])]
    public function login(UserInterface $user): JsonResponse
    {
        return new JsonResponse(['token' => $this->getTokenForUser($user)]);
    }

    #[Route('/logout', name: 'user_logout', methods: ['POST'])]
    public function logout(): void
    {

    }

    private function getTokenForUser(UserInterface $user): string
    {
        return $this->get('lexik_jwt_authentication.jwt_manager')->create($user);
    }
}

<?php

namespace App\Controller;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\User\User;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface;

class AuthenticationController extends AbstractController
{
    #[Route('/apiPlatform/login_check', name: 'api_login_check', methods: ['POST'])]
    public function loginCheck(
        Request $request,
        EntityManagerInterface $entityManager,
        JWTTokenManagerInterface $JWTManager,
        UserPasswordEncoderInterface $passwordEncoder 
    ): JsonResponse {
        $data = json_decode($request->getContent(), true);
    
        if (!is_array($data) || !isset($data['mail']) || !isset($data['password'])) {
            return new JsonResponse(['error' => 'Email et mot de passe requis.'], Response::HTTP_BAD_REQUEST);
        }
    
        $userRepository = $entityManager->getRepository(User::class);
        $user = $userRepository->findOneBy(['mail' => $data['mail']]);
    
        if (!$user) {
            return new JsonResponse(['error' => 'Identifiants invalides.'], Response::HTTP_UNAUTHORIZED);
        }
    
        if (!$passwordEncoder->isPasswordValid($user, $data['password'])) {
            return new JsonResponse(['error' => 'Identifiants invalides.'], Response::HTTP_UNAUTHORIZED);
        }
    
        $jwt = $JWTManager->create($user);
    
        return new JsonResponse(['token' => $jwt]);
    }
}

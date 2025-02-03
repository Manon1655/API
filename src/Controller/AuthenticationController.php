<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;
use Symfony\Component\Security\Core\Authentication\AuthenticationManagerInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\HttpFoundation\Response;

class AuthenticationController extends AbstractController
{
    /**
     * @Route("/api/login_check", name="api_login_check", methods={"POST"})
     */
    public function loginCheck(Request $request, AuthenticationManagerInterface $authenticationManager, JWTTokenManagerInterface $JWTManager): JsonResponse
    {
        // Récupération des données de la requête JSON (username, password)
        $data = json_decode($request->getContent(), true);

        // Validation des données
        if (!isset($data['username']) || !isset($data['password'])) {
            return new JsonResponse(['error' => 'Username and password are required.'], Response::HTTP_BAD_REQUEST);
        }

        // Création du token pour l'authentification avec le firewall 'main' (assurez-vous qu'il existe dans security.yaml)
        $usernamePasswordToken = new UsernamePasswordToken(
            $data['username'],
            $data['password'],
            'main'  // Assurez-vous que 'main' correspond au nom du firewall dans votre configuration.
        );

        try {
            // Authentification avec l'AuthenticationManager
            $authenticatedToken = $authenticationManager->authenticate($usernamePasswordToken);

            // Générer le JWT avec le token authentifié
            $jwt = $JWTManager->create($authenticatedToken);

            // Retourne le JWT dans la réponse
            return new JsonResponse(['token' => $jwt]);
        } catch (AuthenticationException $e) {
            // Retourner une erreur si l'authentification échoue
            return new JsonResponse(['error' => 'Invalid credentials.'], Response::HTTP_UNAUTHORIZED);
        }
    }
}

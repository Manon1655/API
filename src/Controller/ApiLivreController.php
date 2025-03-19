<?php

namespace App\Controller;

use App\Entity\Livre;
use App\Repository\LivreRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ApiLivreController extends AbstractController
{
    /**
     * @Route("/api/livres", name="api_livres", methods={"GET"})
     */
    public function list(LivreRepository $repo, SerializerInterface $serializer): JsonResponse
    {
        $livres = $repo->findAll();

        $resultat = $serializer->serialize(
            $livres,
            'json',
            [
                'groups' => ['listLivreFull']
            ]
        );
        return new JsonResponse($resultat, 200, [], true);
        // return $this->render('api_livre/livre.html.twig', [
        //     'controller_name' => 'ApiLivreController','livres' => json_decode($resultat, true)
        // ]);
    }

    /**
     * @Route("/api/livres/{id}", name="api_livres_show", methods={"GET"})
     */
    public function show(Livre $livre, SerializerInterface $serializer): JsonResponse
    {
        $resultat = $serializer->serialize(
            $livre,
            'json',
            [
                'groups' => ['listLivreSimple']
            ]
        );

        return new JsonResponse($resultat, Response::HTTP_OK, [], true);
    }

    /**
     * @Route("/api/livres", name="api_livres_create", methods={"POST"})
     */
    public function create(Request $request, EntityManagerInterface $manager, SerializerInterface $serializer, ValidatorInterface $validator): JsonResponse
    {
        $data = $request->getContent();
        // $livre=new Livre();
        // $serializer->deserialize($data, Livre::class,'json',['object_to_populate'=>$livre]);
        $livre = $serializer->deserialize($data, Livre::class, 'json');

        // gestion des erreurs de validation 
        $errors = $validator->validate($livre);
        if (count($errors)) {
            $errorsJson = $serializer->serialize($errors, 'json');
            return new JsonResponse($errorsJson, Response::HTTP_BAD_REQUEST, [], true);
        }

        $manager->persist($livre);
        $manager->flush();

        return new JsonResponse(
            "Le livre a bien été créé",
            Response::HTTP_CREATED,
            // ["location"=>"api/livres/".$livre->getId()],
            ["location" => $this->generateUrl(
                'api_livres_show',
                ["id" => $livre->getId()],
                UrlGeneratorInterface::ABSOLUTE_URL
            )],
            true
        );
    }

    /**
     * @Route("/api/livres/{id}", name="api_livres_update", methods={"PUT"})
     */
    public function edit(Livre $livre, Request $request, EntityManagerInterface $manager, SerializerInterface $serializer, ValidatorInterface $validator): JsonResponse
    {
        $data = $request->getContent();
        $serializer->deserialize($data, Livre::class, 'json', ['object_to_populate' => $livre]);

        // gestion des erreurs de validation 
        $errors = $validator->validate($livre);
        if (count($errors)) {
            $errorsJson = $serializer->serialize($errors, 'json');
            return new JsonResponse($errorsJson, Response::HTTP_BAD_REQUEST, [], true);
        }

        $manager->persist($livre);
        $manager->flush();

        return new JsonResponse("le livre a bien été modifié", Response::HTTP_OK, [], true);
    }

    /**
     * @Route("/api/livres/{id}", name="api_livres_delete", methods={"DELETE"})
     */
    public function delete(Livre $livre, EntityManagerInterface $manager): JsonResponse
    {
        $manager->remove($livre);
        $manager->flush();

        return new JsonResponse("Le livre a bien été supprimé", Response::HTTP_OK, []);
    }
}
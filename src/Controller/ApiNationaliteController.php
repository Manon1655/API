<?php

namespace App\Controller;

use App\Entity\Nationalite;
use App\Repository\NationaliteRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ApiNationaliteController extends AbstractController
{
    /**
     * @Route("/api/nationalites", name="api_nationalites", methods={"GET"})
     */
    public function list(NationaliteRepository $repo, SerializerInterface $serializer): JsonResponse
    {
        $nationalites = $repo->findAll();
        $resultat = $serializer->serialize(
            $nationalites,
            'json',
            [
                'groups' => ['listNationaliteFull']
            ]
        );
        return new JsonResponse($resultat, 200, [], true);
        // return $this->render('api_nationalite/nationalite.html.twig', [
        //     'controller_name' => 'ApiNationaliteController','nationalites' => json_decode($resultat, true)
        // ]);
    }

    /**
     * @Route("/api/nationalites/{id}", name="api_nationalites_show", methods={"GET"})
     */
    public function show(Nationalite $nationalite, SerializerInterface $serializer): JsonResponse
    {
        $resultat = $serializer->serialize(
            $nationalite,
            'json',
            [
                'groups' => ['listNationaliteSimple']
            ]
        );

        // return new JsonResponse($resultat, Response::HTTP_OK, [], true);
        return $this->render('api_nationalite/nationalite.html.twig', [
            'controller_name' => 'ApiNationaliteController',
            'nationalites' => json_decode($resultat, true)
        ]);
    }

    /**
     * @Route("/api/nationalites", name="api_nationalites_create", methods={"POST"})
     */
    public function create(Request $request, EntityManagerInterface $manager, SerializerInterface $serializer, ValidatorInterface $validator): JsonResponse
    {
        $data = $request->getContent();
        // $nationalite=new Nationalite();
        // $serializer->deserialize($data, Nationalite::class,'json',['object_to_populate'=>$nationalite]);
        $nationalite = $serializer->deserialize($data, Nationalite::class, 'json');

        // gestion des erreurs de validation 
        $errors = $validator->validate($nationalite);
        if (count($errors)) {
            $errorsJson = $serializer->serialize($errors, 'json');
            return new JsonResponse($errorsJson, Response::HTTP_BAD_REQUEST, [], true);
        }

        $manager->persist($nationalite);
        $manager->flush();

        return new JsonResponse(
            "Le nationalite a bien été créé",
            Response::HTTP_CREATED,
            // ["location"=>"api/nationalites/".$nationalite->getId()],
            ["location" => $this->generateUrl(
                'api_nationalites_show',
                ["id" => $nationalite->getId()],
                UrlGeneratorInterface::ABSOLUTE_URL
            )],
            true
        );
    }

    /**
     * @Route("/api/nationalites/{id}", name="api_nationalites_update", methods={"PUT"})
     */
    public function edit(Nationalite $nationalite, Request $request, EntityManagerInterface $manager, SerializerInterface $serializer, ValidatorInterface $validator): JsonResponse
    {
        $data = $request->getContent();
        $serializer->deserialize($data, Nationalite::class, 'json', ['object_to_populate' => $nationalite]);

        // gestion des erreurs de validation 
        $errors = $validator->validate($nationalite);
        if (count($errors)) {
            $errorsJson = $serializer->serialize($errors, 'json');
            return new JsonResponse($errorsJson, Response::HTTP_BAD_REQUEST, [], true);
        }

        $manager->persist($nationalite);
        $manager->flush();

        return new JsonResponse("la nationalite a bien été modifié", Response::HTTP_OK, [], true);
    }

    /**
     * @Route("/api/nationalites/{id}", name="api_nationalites_delete", methods={"DELETE"})
     */
    public function delete(Nationalite $nationalite, EntityManagerInterface $manager): JsonResponse
    {
        $manager->remove($nationalite);
        $manager->flush();

        return new JsonResponse("La nationalite a bien été supprimé", Response::HTTP_OK, []);
    }
}

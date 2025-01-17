<?php

namespace App\Controller;

use App\Entity\Editeur;
use App\Repository\EditeurRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ApiEditeurController extends AbstractController
{
    /**
     * @Route("/api/editeurs", name="api_editeurs", methods={"GET"})
     */
    public function list(EditeurRepository $repo, SerializerInterface $serializer)
    {
        $editeurs = $repo->findAll();
        $resultat = $serializer->serialize(
            $editeurs,
            'json',
            [
                'groups' => ['listEditeurFull']
            ]
        );
        return new JsonResponse($resultat, 200, [], true);
    }

    /**
     * @Route("/api/editeurs/{id}", name="api_editeurs_show", methods={"GET"})
     */
    public function show(Editeur $editeur, SerializerInterface $serializer)
    {
        $resultat = $serializer->serialize(
            $editeur,
            'json',
            [
                'groups' => ['listEditeurSimple']
            ]
        );

        return new JsonResponse($resultat, Response::HTTP_OK, [], true);
    }

    /**
     * @Route("/api/editeurs", name="api_editeurs_create", methods={"POST"})
     */
    public function create(Request $request, EntityManagerInterface $manager, SerializerInterface $serializer, ValidatorInterface $validator)
    {
        $data = $request->getContent();
        $editeur = new Editeur();
        $serializer->deserialize($data, Editeur::class, 'json', ['object_to_populate' => $editeur]);

        $errors = $validator->validate($editeur);
        if (count($errors)) {
            $errorsJson = $serializer->serialize($errors, 'json');
            return new JsonResponse($errorsJson, Response::HTTP_BAD_REQUEST, [], true);
        }

        $manager->persist($editeur);
        $manager->flush();

        return new JsonResponse(
            "L'éditeur a bien été créé", 
            Response::HTTP_CREATED,
            [
                "location" => $this->generateUrl(
                    'api_editeurs_show',
                    ["id" => $editeur->getId()],
                    UrlGeneratorInterface::ABSOLUTE_URL
                )
            ],
            true
        );
    }

    /**
     * @Route("/api/editeurs/{id}", name="api_editeurs_update", methods={"PUT"})
     */
    public function edit(Editeur $editeur, Request $request, EntityManagerInterface $manager, SerializerInterface $serializer, ValidatorInterface $validator)
    {
        $data = $request->getContent();
        $serializer->deserialize($data, Editeur::class, 'json', ['object_to_populate' => $editeur]);

        $errors = $validator->validate($editeur);
        if (count($errors)) {
            $errorsJson = $serializer->serialize($errors, 'json');
            return new JsonResponse($errorsJson, Response::HTTP_BAD_REQUEST, [], true);
        }
        $manager->persist($editeur);
        $manager->flush();

        return new JsonResponse("L'éditeur a bien été modifié", Response::HTTP_OK, [], true); 
    }

    /**
     * @Route("/api/editeurs/{id}", name="api_editeurs_delete", methods={"DELETE"})
     */
    public function delete(Editeur $editeur, EntityManagerInterface $manager)
    {
        $manager->remove($editeur);
        $manager->flush();

        return new JsonResponse("L'éditeur a bien été supprimé", Response::HTTP_OK, []);
    }
}

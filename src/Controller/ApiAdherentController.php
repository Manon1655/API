<?php

namespace App\Controller;

use App\Entity\Adherent;
use App\Repository\AdherentRepository;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\NationaliteRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ApiAdherentController extends AbstractController
{
    /**
     * @Route("/api/adherents", name="api_adherents", methods={"GET"})
     */
    public function list(AdherentRepository $repo, SerializerInterface $serializer)
    {
        $adherents = $repo->findAll();
        $resultat = $serializer->serialize(
            $adherents,
            'json',
            [
                'groups' => ['listAdherentFull']
            ]
        );
        return new JsonResponse($resultat, 200, [], true);
    }

    /**
     * @Route("/api/adherents/{id}", name="api_adherents_show", methods={"GET"})
     */
    public function show(Adherent $adherent, SerializerInterface $serializer)
    {
        $resultat = $serializer->serialize(
            $adherent,
            'json',
            [
                'groups' => ['listAdherentSimple']
            ]
        );

        return new JsonResponse($resultat, Response::HTTP_OK, [], true);
    }

    /**
     * @Route("/api/adherents", name="api_adherents_create", methods={"POST"})
     */
    public function create(Request $request, NationaliteRepository $repoNation, EntityManagerInterface $manager, SerializerInterface $serializer, ValidatorInterface $validator)
    {
        $data = $request->getContent();
        $dataTab = $serializer->decode($data, 'json');

        $adherent = new Adherent();
        $Relation = $repoNation->find($dataTab['nationalite']['id']);
        $serializer->deserialize($data, Adherent::class, 'json', ['object_to_populate' => $adherent]);
        $adherent->setRelation($Relation);
        $errors = $validator->validate($adherent);
        if (count($errors)) {
            $errorsJson = $serializer->serialize($errors, 'json');
            return new JsonResponse($errorsJson, Response::HTTP_BAD_REQUEST, [], true);
        }

        $manager->persist($adherent);
        $manager->flush();

        return new JsonResponse(
            "L'adherent a bien été créé",
            Response::HTTP_CREATED,
            [
                "location" => $this->generateUrl(
                    'api_adherents_show',
                    ["id" => $adherent->getId()],
                    UrlGeneratorInterface::ABSOLUTE_URL
                )
            ],
            true
        );
    }

    /**
     * @Route("/api/adherents/{id}", name="api_adherents_update", methods={"PUT"})
     */
    public function edit(Adherent $adherent, NationaliteRepository $repoNation, Request $request, EntityManagerInterface $manager, SerializerInterface $serializer, ValidatorInterface $validator)
    {
        $data = $request->getContent();
        $dataTab = $serializer->decode($data, 'json');
        $Relation = $repoNation->find($dataTab['nationalite']['id']);
        $serializer->deserialize($data, Adherent::class, 'json', ['object_to_populate' => $adherent]);
        $adherent->setRelation($Relation);
        $errors = $validator->validate($adherent);
        if (count($errors)) {
            $errorsJson = $serializer->serialize($errors, 'json');
            return new JsonResponse($errorsJson, Response::HTTP_BAD_REQUEST, [], true);
        }

        $manager->persist($adherent);
        $manager->flush();

        return new JsonResponse("L'adherent a bien été modifié", Response::HTTP_OK, [], true); 
    }

    /**
     * @Route("/api/adherents/{id}", name="api_adherents_delete", methods={"DELETE"})
     */
    public function delete(Adherent $adherent, EntityManagerInterface $manager)
    {
        $manager->remove($adherent);
        $manager->flush();

        return new JsonResponse("L'adherent a bien été supprimé", Response::HTTP_OK, []);
    }
}

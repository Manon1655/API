<?php

namespace App\Controller;

use App\Entity\Adherent;
use App\Repository\PretRepository;
use App\Repository\AdherentRepository;
use Doctrine\ORM\EntityManagerInterface;
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
    public function listAdherents(AdherentRepository $adherentRepository, SerializerInterface $serializer): JsonResponse
    {
        $adherents = $adherentRepository->findAll(); 
        $resultat = $serializer->serialize(
            $adherents,
            'json',
            [
                'groups' => ['listAdherentFull']
            ]
        );
        return new JsonResponse($resultat, Response::HTTP_OK, [], true);
        // return $this->render('api_adherent/adherent.html.twig', [
        //     'controller_name' => 'ApiAdherentController','adherent' => json_decode($resultat, true)
        // ]);
    }
    /**
     * @Route("/api/adherents/{id}", name="api_adherents_show", methods={"GET"})
     */
    public function show(Adherent $adherent, SerializerInterface $serializer): JsonResponse
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
    public function create(Request $request, PretRepository $repopret, EntityManagerInterface $manager, SerializerInterface $serializer, ValidatorInterface $validator): JsonResponse
    {
        $data = $request->getContent();
        $dataTab = json_decode($data, true);
        $adherent = $serializer->deserialize($data, Adherent::class, 'json');
        
        if (isset($dataTab['pret']['id'])) {
            $pret = $repopret->find($dataTab['pret']['id']);
            if (!$pret) {
                return new JsonResponse("Prêt invalide ou introuvable", Response::HTTP_BAD_REQUEST);
            }
            $adherent->addPret($pret); 
        }
        $errors = $validator->validate($adherent);
        if (count($errors)) {
            $errorsJson = $serializer->serialize($errors, 'json');
            return new JsonResponse($errorsJson, Response::HTTP_BAD_REQUEST, [], true);
        }
        $manager->persist($adherent);
        $manager->flush();
        
        return new JsonResponse(
            "L'adhérent a bien été créé",
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
    public function edit(Adherent $adherent, Request $request, EntityManagerInterface $manager, SerializerInterface $serializer, ValidatorInterface $validator): JsonResponse
    {
        $data = $request->getContent();
        $serializer->deserialize($data, Adherent::class, 'json', ['object_to_populate' => $adherent]);
        
        $errors = $validator->validate($adherent);
        if (count($errors)) {
            $errorsJson = $serializer->serialize($errors, 'json');
            return new JsonResponse($errorsJson, Response::HTTP_BAD_REQUEST, [], true);
        }
        $manager->persist($adherent);
        $manager->flush();
    
        return new JsonResponse("L'adhérent a bien été modifié", Response::HTTP_OK, [], true); 
    }

    /**
     * @Route("/api/adherents/{id}", name="api_adherents_delete", methods={"DELETE"})
     */
    public function delete(Adherent $adherent, EntityManagerInterface $manager): JsonResponse
    {
        $manager->remove($adherent);
        $manager->flush();

        return new JsonResponse("L'adhérent a bien été supprimé", Response::HTTP_OK, []);
    }
}
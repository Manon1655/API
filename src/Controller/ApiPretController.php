<?php

namespace App\Controller;

use App\Entity\Pret;
use App\Repository\PretRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ApiPretController extends AbstractController
{
    /**
     * @Route("/api/prets", name="api_prets", methods={"GET"})
     */
    public function list(PretRepository $repo, SerializerInterface $serializer)
    {
        $prets = $repo->findAll();
        $resultat = $serializer->serialize(
            $prets,
            'json',
            [
                'groups' => ['listPretFull']
            ]
        );
        // return new JsonResponse($resultat,200,[],true);
        return $this->render('api_pret/pret.html.twig', [
            'controller_name' => 'ApiPretController','prets' => json_decode($resultat, true)
        ]);
    }

    /**
     * @Route("/api/prets/{id}", name="api_prets_show", methods={"GET"})
     */
    public function show(Pret $pret, SerializerInterface $serializer)
    {
        $resultat = $serializer->serialize(
            $pret,
            'json',
            [
                'groups' => ['listPretSimple']
            ]
        );

        return new JsonResponse($resultat, Response::HTTP_OK, [], true);
    }

    /**
     * @Route("/api/prets", name="api_prets_create", methods={"POST"})
     */
    public function create(Request $request, EntityManagerInterface $manager, SerializerInterface $serializer, ValidatorInterface $validator)
    {
        $data=$request->getContent();
        // $pret=new Pret();
        // $serializer->deserialize($data, Pret::class,'json',['object_to_populate'=>$pret]);
        $pret=$serializer->deserialize($data, Pret::class,'json');

        // gestion des erreurs de validation 
        $errors=$validator->validate($pret);
        if(count($errors)){
            $errorsJson=$serializer->serialize($errors,'json');
            return new JsonResponse($errorsJson, Response::HTTP_BAD_REQUEST,[],true);
        }

        $manager->persist($pret);
        $manager->flush();

        return new JsonResponse(
            "Le pret a bien été créé",
            Response::HTTP_CREATED,
            // ["location"=>"api/prets/".$pret->getId()],
            ["location"=> $this->generateUrl(
                    'api_prets_show',
                ["id"=>$pret->getId()],
                UrlGeneratorInterface::ABSOLUTE_URL)],
            true);
    }

    /**
     * @Route("/api/prets/{id}", name="api_prets_update", methods={"PUT"})
     */
    public function edit(Pret $pret,Request $request,EntityManagerInterface $manager, SerializerInterface $serializer, ValidatorInterface $validator)
    {
        $data=$request->getContent();
        $serializer->deserialize($data, Pret::class,'json',['object_to_populate'=>$pret]);

        // gestion des erreurs de validation 
        $errors=$validator->validate($pret);
        if(count($errors)){
            $errorsJson=$serializer->serialize($errors,'json');
            return new JsonResponse($errorsJson, Response::HTTP_BAD_REQUEST,[],true);
        }

        $manager->persist($pret);
        $manager->flush();

        return new JsonResponse("la pret a bien été modifié",Response::HTTP_OK,[],true); 
    }

    /**
     * @Route("/api/prets/{id}", name="api_prets_delete", methods={"DELETE"})
     */
    public function delete(Pret $pret,EntityManagerInterface $manager)
    {
        $manager->remove($pret);
        $manager->flush();

        return new JsonResponse("La pret a bien été supprimé", Response::HTTP_OK, []);
    }
}

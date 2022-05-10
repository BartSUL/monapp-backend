<?php

namespace App\Controller;

use App\Entity\People;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class IndexController extends AbstractController
{
    #[Route('/index', name: 'app_index')]
    public function index(ManagerRegistry $doctrine): Response
    {
        $repository = $doctrine->getRepository(People::class);
        $peoples = $repository->findAll();

        $payload = [];
        foreach($peoples as $people) {
            array_push($payload, $people->getName());
        }
        return new JsonResponse([
            'message' => 'Welcome to your new controller!',
            'peoples' => $payload,
        ]);
    }

    #[Route('/people/add', name: 'app_add_people', methods: ['POST'])]
    public function addPeople(Request $request, ManagerRegistry $doctrine): Response
    {
        $postData = $request->toArray();

        $vip = new People();
        $vip->setName($postData['name']);

        $em = $doctrine->getManager();
        $em->persist($vip);
        $em->flush();

        return new JsonResponse([
            'message' => 'Welcome to app_add_people!',
            'name' => $postData['name'],
        ]);
    }
}

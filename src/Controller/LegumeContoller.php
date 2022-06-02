<?php

namespace App\Controller;

use App\Entity\Legume;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class LegumeContoller extends AbstractController
{
    #[Route('/legume', name: 'app_legume')]
    public function legume(ManagerRegistry $doctrine): Response
    {
        $repository = $doctrine->getRepository(Legume::class);
        $legumes = $repository->findAll();

        $payload = [];
        foreach($legumes as $legume) {
            array_push($payload, $legume->getName());
        }
        return new JsonResponse([
            'message' => 'Welcome to your new controller!',
            'legumes' => $payload,
        ]);
    }

    #[Route('/legume/add', name: 'app_add_legume', methods: ['POST'])]
    public function addlegume(Request $request, ManagerRegistry $doctrine): Response
    {
        $postData = $request->toArray();

        $vip = new Legume();
        $vip->setName($postData['name']);

        $em = $doctrine->getManager();
        $em->persist($vip);
        $em->flush();

        return new JsonResponse([
            'message' => 'Welcome to app_add_legume!',
            'name' => $postData['name'],
        ]);
    }

    #[Route('/legume/{name}', name: 'app_remove_legume', methods: ['DELETE'])]
    public function removelegume(string $name, Request $request, ManagerRegistry $doctrine): Response
    {
        $repository = $doctrine->getRepository(Legume::class);
        $vip = $repository->findOneBy([ 'name' => $name ]);

        $em = $doctrine->getManager();
        $em->remove($vip);
        $em->flush();

        return new JsonResponse([
            'message' => 'Welcome to app_remove_legume!',
            'name' => $name,
        ]);
    }
}

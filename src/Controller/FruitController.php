<?php

namespace App\Controller;

use App\Entity\Fruits;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class FruitController extends AbstractController
{
    #[Route('/fruit', name: 'app_fruit')]
    public function fruits(ManagerRegistry $doctrine): Response
    {
        $repository = $doctrine->getRepository(Fruits::class);
        $Fruits = $repository->findAll();

        $payload = [];
        foreach($Fruits as $Fruit) {
            array_push($payload, $Fruit->getName());
        }
        return new JsonResponse([
            'message' => 'Welcome to your new controller!',
            'Fruits' => $payload,
        ]);
    }

    #[Route('/fruit/add', name: 'app_add_fruit', methods: ['POST'])]
    public function addFruit(Request $request, ManagerRegistry $doctrine): Response
    {
        $postData = $request->toArray();

        $vip = new Fruits();
        $vip->setName($postData['name']);

        $em = $doctrine->getManager();
        $em->persist($vip);
        $em->flush();

        return new JsonResponse([
            'message' => 'Welcome to app_add_Fruit!',
            'name' => $postData['name'],
        ]);
        var_dump($vip);
    }

    #[Route('/fruit/{name}', name: 'app_remove_fruit', methods: ['DELETE'])]
    public function removeFruit(string $name, Request $request, ManagerRegistry $doctrine): Response
    {
        $repository = $doctrine->getRepository(Fruits::class);
        $vip = $repository->findOneBy([ 'name' => $name ]);

        $em = $doctrine->getManager();
        $em->remove($vip);
        $em->flush();

        return new JsonResponse([
            'message' => 'Welcome to app_remove_Fruit!',
            'name' => $name,
        ]);
    }
}
<?php

namespace App\Controller;

use App\Entity\People;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;

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
    public function addPeople(Request $request, ManagerRegistry $doctrine, ValidatorInterface $validator): Response
    {
        $postData = $request->toArray();

        $vip = new People();
        $vip->setName($postData['name']);

        $errors = $validator->validate($vip);
        if (count($errors) > 0) {

            $payload = [];
            foreach($errors as $error) {
                array_push($payload, $error->getMessage());
                array_push($payload, $error->getMessage());
                array_push($payload, $error->getMessage());
            }

            return new JsonResponse([
                'message' => 'Validation Error',
                'errors' => $payload,
            ], 400);
        }

        $em = $doctrine->getManager();
        $em->persist($vip);
        $em->flush();

        return new JsonResponse([
            'message' => 'Welcome to app_add_people!',
            'name' => $postData['name'],
        ]);
    }

    #[Route('/people/{name}', name: 'app_remove_people', methods: ['DELETE'])]
    public function removePeople(string $name, Request $request, ManagerRegistry $doctrine): Response
    {
        $repository = $doctrine->getRepository(People::class);
        $decoded = base64_decode($name);
        $vipToDelete = $repository->findBy([ 'name' => $decoded ]);

        $em = $doctrine->getManager();
        foreach ($vipToDelete as $vip) {
            $em->remove($vip);
        }
        $em->flush();

        return new JsonResponse([
            'message' => 'Welcome to app_remove_people!',
            'name' => $name,
        ]);
    }
}

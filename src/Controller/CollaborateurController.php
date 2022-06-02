<?php

namespace App\Controller;

use App\Entity\Collaborateur;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CollaborateurController extends AbstractController
{
    #[Route('/collaborateur', name: 'app_collaborateur', methods: ['GET'])]
    public function collaborateur(ManagerRegistry $doctrine): Response
    {
        $repository = $doctrine->getRepository(Collaborateur::class);
        $collaborateurs = $repository->findAll();

        $payload = [];
        foreach ($collaborateurs as $collaborateur) {
            array_push($payload, [
                "title" => $collaborateur->getTitle(),
                "subtitle" => $collaborateur->getSubtitle(),
                "description" => $collaborateur->getDescription(),
                "image" => $collaborateur->getImage(),
            ]);
        }
        return new JsonResponse([
            'message' => 'Welcome to your new controller!',
            'collaborateurs' => $payload,
        ]);
    }

    #[Route('/collaborateur/add', name: 'app_add_Collaborateur', methods: ['POST'])]
    public function addCollaborateur(Request $request, ManagerRegistry $doctrine): Response
    {
        $postData = $request->toArray();

        $vip = new Collaborateur();
        $vip->setTitle($postData['title']);
        $vip->setSubtitle($postData['subtitle']);
        $vip->setDescription($postData['description']);
        $vip->setImage($postData['image']);

        $em = $doctrine->getManager();
        $em->persist($vip);
        $em->flush();

        return new JsonResponse([
            'message' => 'Welcome to app_add_collaborateur!',
            'title' => $postData['title'],
            'subtitle' => $postData['subtitle'],
            'description' => $postData['description'],
            'image' => $postData['image'],
        ]);
    }

    #[Route('/collaborateur/{title}', name: 'app_remove_collaborateur', methods: ['DELETE'])]
    public function removecollaborateur(string $title, string $subtitle, string $description, string $image, Request $request, ManagerRegistry $doctrine): Response
    {
        $postData = $request->toArray();

        $repository = $doctrine->getRepository(Collaborateur::class);
        $vip = $repository->findOneBy(['title' => $title]);
        $vip = $repository->findOneBy(['subtitle' => $subtitle]);
        $vip = $repository->findOneBy(['description' => $description]);
        $vip = $repository->findOneBy(['image' => $image]);

        $em = $doctrine->getManager();
        $em->remove($vip);
        $em->flush();

        return new JsonResponse([
            'message' => 'Welcome to app_remove_collaborateur!',
            'title' => $postData['title'],
            'subtitle' => $postData['subtitle'],
            'description' => $postData['description'],
            'image' => $postData['image'],
        ]);
    }
}

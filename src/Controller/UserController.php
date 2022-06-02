<?php

namespace App\Controller;

use App\Entity\User;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class UserController extends AbstractController
{
    #[Route('/user', name: 'app_user')]
    public function user(ManagerRegistry $doctrine): Response
    {
        $repository = $doctrine->getRepository(User::class);
        $users = $repository->findAll();

        $payload = [];
        foreach ($users as $user) {
            // $userData = [];
            // array_push($userData, $user->getEmail());
            // array_push($userData, $user->getFirstname());
            // array_push($userData, $user->getLastname());
            // array_push($userData, $user->getPassword());
            // array_push($userData, $user->getPhone());
            // array_push($userData, $user->getAbout());
            array_push($payload, [
                "email" => $user->getEmail(),
                "firstNAme" => $user->getFirstname(),
                "lastName" => $user->getLastname(),
                "password" => $user->getPassword(),
                "Phone" => $user->getPhone(),
                "About" => $user->getAbout(),
            ]);
        }
        return new JsonResponse([
            'message' => 'Welcome to your new controller!',
            'users' => $payload,
        ]);
    }

    #[Route('/user/add', name: 'app_add_user', methods: ['POST'])]
    public function adduser(Request $request, ManagerRegistry $doctrine): Response
    {
        $postData = $request->toArray();

        $vip = new User();
        $vip->setEmail($postData['email']);
        $vip->setFirstname($postData['firstname']);
        $vip->setLastname($postData['lastname']);
        $vip->setPassword($postData['password']);
        $vip->setPhone($postData['phone']);
        $vip->setAbout($postData['about']);

        $em = $doctrine->getManager();
        $em->persist($vip);
        $em->flush();

        return new JsonResponse([
            'message' => 'Welcome to app_add_user!',
            'email' => $postData['email'],
            'firstname' => $postData['firstname'],
            'lastname' => $postData['lastname'],
            'role' => $postData['role'],
            'password' => $postData['password'],
            'phone' => $postData['phone'],
            'about' => $postData['about'],
        ]);
        var_dump($vip);
    }

    #[Route('/user/{name}', name: 'app_remove_user', methods: ['DELETE'])]
    public function removeuser(string $name, Request $request, ManagerRegistry $doctrine): Response
    {
        $repository = $doctrine->getRepository(User::class);
        $vip = $repository->findOneBy(['name' => $name]);

        $em = $doctrine->getManager();
        $em->remove($vip);
        $em->flush();

        return new JsonResponse([
            'message' => 'Welcome to app_remove_user!',
            'name' => $name,
        ]);
    }
}

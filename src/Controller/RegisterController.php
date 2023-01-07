<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\RegisterType;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;

class RegisterController extends AbstractController
{
    public function index(
        Request $request,
        UserPasswordHasherInterface $userPasswordHasher,
        UserRepository $userRepository
    ) {
        try {
            $user = new User();
            $form = $this->createForm(RegisterType::class, $user);
            $form->handleRequest($request); //vérifie le bon format des variables super globaux
            if ($form->isSubmitted() && $form->isValid()) {
                $user = $form->getData();
                $hashedPassword = $userPasswordHasher->hashPassword(
                    $user,
                    $form->getData()
                        ->getPassword()
                );
                $user->setPassword($hashedPassword);
                $userRepository->save($user, true);
            }
        } catch (\Throwable $th) {
            throw $th;
        }

        return $this->render('register/index.html.twig', [
            'form' => $form,
        ]);
    }
}

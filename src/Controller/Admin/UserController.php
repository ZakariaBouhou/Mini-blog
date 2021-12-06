<?php

namespace App\Controller\Admin;

use App\Entity\User;
use App\Form\UserType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;

class UserController extends AbstractController 
{

    /**
     * @Route("/inscription", name="inscription")
     */
    public function add(Request $request, UserPasswordHasherInterface $encoder, EntityManagerInterface $em): Response
    {
        $user = new User;

        $form = $this->createForm(UserType::class, $user);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $user->setPseudo($form->get('pseudo')->getData());
            $user->setEmail($form->get('email')->getData());
            
            $user->setRoles(['ROLE_ADMIN']);

            $hash = $encoder->hashPassword($user, $user->getPassword());
            $user->setPassword($hash);

            $em->persist($user);
            $em->flush();

            $this->redirectToRoute('admin_article_browse');
        }

        return $this->render('admin/inscription.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
<?php

namespace App\Controller\Admin;

use App\Entity\Article;
use App\Form\EditArticleType;
use App\Form\NewArticleType;
use App\Repository\ArticleRepository;
use App\Service\ArticleSlugger;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Csrf\CsrfToken;
use Symfony\Component\Security\Csrf\CsrfTokenManager;
use Symfony\Component\Security\Csrf\CsrfTokenManagerInterface;

/**
 * @Route("/admin/article", name="admin_article_")
 */
class AdminController extends AbstractController
{ 
    /**
     * @Route("/add", name="add")
     */
    public function create(Article $article = null, Request $request, EntityManagerInterface $em, ArticleSlugger $articleSlugger): Response
    {
        
        $article = new Article();
    
        $form = $this->createForm(NewArticleType::class, $article); 
        
        $form->handleRequest($request);
     
        if($form->isSubmitted() && $form->isValid()) {
            
            $article->setCreatedAt(new DateTime());

            $article->setPicture($form->get('picture')->getData());          
            $article->setTitle($form->get('title')->getData()); 
            
            $user = $this->getUser();
            $article->setUserid($user);
            
            $slug = $articleSlugger->slugify($form->get('title')->getData());
            $article->setSlug($slug);

            $em->persist($article);
            $em->flush();

            return $this->redirectToRoute('admin_home');
        }

        return $this->render('article/add.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{slug}/edit", name="edit")
     */
    public function edit(Article $article, Request $request, ArticleSlugger $articleSlugger): Response
    {
        $form = $this->createForm(EditArticleType::class, $article);
        
        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            $em = $this->getDoctrine()->getManager();

            $slug = $articleSlugger->slugify($form->get('title')->getData());
            $article->setSlug($slug);

            $article = $form->getData();
            $em->flush();

            return $this->redirectToRoute('admin_home');
        }

        return $this->render('article/edit.html.twig', [          
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{slug}/delete", name="delete")
     */
    public function delete(Article $article, Request $request, CsrfTokenManagerInterface $csrfTokenManagerInterface): Response
    {       
       $token = new CsrfToken('deleteArticle', $request->query->get('_csrf_token'));

       if ($csrfTokenManagerInterface->isTokenValid($token)) {      

            $em = $this->getDoctrine()->getManager();
            $em->remove($article);
            $em->flush();

            return $this->redirectToRoute('admin_home');
        }
       
    }

}

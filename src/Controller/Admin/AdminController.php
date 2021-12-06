<?php

namespace App\Controller\Admin;

use App\Entity\Article;
use App\Form\EditArticleType;
use App\Form\NewArticleType;
use App\Repository\ArticleRepository;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/admin/article", name="admin_article_")
 */
class AdminController extends AbstractController
{
    /**
     * @Route("/", name="browse")
     */
    public function browse(ArticleRepository $articleRepository): Response
    {
        $allArticle = $articleRepository->findAll();

        return $this->render('admin/browse.html.twig', [
            'articles' => $allArticle,
        ]);
    }

    /**
     * @Route("/add", name="add")
     */
    public function create(Article $article = null, Request $request, EntityManagerInterface $em): Response
    {
        
        $article = new Article();
    
        $form = $this->createForm(NewArticleType::class, $article); 
        
        $form->handleRequest($request);

        //dump($form);

        
        if($form->isSubmitted() && $form->isValid()) {
            
            $article->setCreatedAt(new DateTime());

            $article->setPicture($form->get('picture')->getData());          
            $article->setTitle($form->get('title')->getData());          

            $em->persist($article);
            $em->flush();

            return $this->redirectToRoute('admin_article_browse');
        }

        return $this->render('article/add.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}/edit", name="edit", requirements={"id" : "\d+"})
     */
    public function edit(Article $article, Request $request): Response
    {
        $form = $this->createForm(EditArticleType::class, $article);
        
        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            $em = $this->getDoctrine()->getManager();
            $article = $form->getData();
            $em->flush();

            return $this->redirectToRoute('admin_article_browse');
        }

        return $this->render('article/edit.html.twig', [          
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}/delete", name="delete", requirements={"id" : "\d+"})
     */
    public function delete(Article $article): Response

    {
        $em = $this->getDoctrine()->getManager();
        $em->remove($article);
        $em->flush();

        return $this->redirectToRoute('admin_article_browse');
    }

}

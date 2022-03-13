<?php

namespace App\Controller\Admin;

use App\Entity\Article;
use App\Form\EditArticleType;
use App\Form\NewArticleType;
use App\Service\ArticleSlugger;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Csrf\CsrfToken;
use Symfony\Component\Security\Csrf\CsrfTokenManagerInterface;

/**
 * @Route("/admin/article", name="admin_article_")
 */
class AdminController extends AbstractController
{ 
    /**
     * @Route("/add", name="add")
     */
    public function create(Request $request, EntityManagerInterface $em, ArticleSlugger $articleSlugger): Response
    {      
        $article = new Article();

        //$img = new Picture();
    
        $form = $this->createForm(NewArticleType::class, $article); 
        
        $form->handleRequest($request);
     
        if($form->isSubmitted() && $form->isValid()) {
            
            $article->setCreatedAt(new DateTime());
           
            $image = $form->get('picture')->getData();   
            
            if ($image) {
                $fichier = md5(uniqid()) . '.' . $image->guessExtension();

                $image->move(
                    $this->getParameter('images_directory'),
                    $fichier, 
                );
                
                $article->setPicture($fichier);
                //$article->setPicture($img);
            }


            $article->setTitle($form->get('title')->getData()); 
            
            $user = $this->getUser();
            $article->setUserid($user);
            
            $slug = $articleSlugger->slugify($form->get('title')->getData());
            $article->setSlug($slug);

            $em = $this->getDoctrine()->getManager();
            $em->persist($article);
            //$em->persist($img);
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

        if ($form->isSubmitted() && $form->isValid()) {

            $image = $form->get('picture')->getData();   
                      
            if ($image) {
                $fichier = md5(uniqid()) . '.' . $image->guessExtension();

                $image->move(
                    $this->getParameter('images_directory'),
                    $fichier, 
                );
                
                $article->setPicture($fichier);
            }
          
            $slug = $articleSlugger->slugify($form->get('title')->getData());
            $article->setSlug($slug);
            
            $article = $form->getData();

            $em = $this->getDoctrine()->getManager();
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

    /**
     * @Route("/{slug}/delete/picture", name="delete_picture")
     */
    public function deletePicture(Article $article, Request $request, CsrfTokenManagerInterface $csrfTokenManagerInterface): Response
    {       
       $token = new CsrfToken('deletePicture', $request->query->get('_csrf_token'));

       if ($csrfTokenManagerInterface->isTokenValid($token)) {      

            $picture = $article->getPicture();
            //dd($picture);

            unlink($this->getParameter('images_directory').'/'.$picture);

            $em = $this->getDoctrine()->getManager();
            $em->remove($picture);
            $em->flush();

            return $this->redirectToRoute('admin_home');
        }
       
    }

}

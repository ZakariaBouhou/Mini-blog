<?php

namespace App\Controller\Admin;

use App\Entity\Article;
use App\Entity\Picture;
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

        $image = new Picture();
        
        $form = $this->createForm(NewArticleType::class, $article); 
        
        $form->handleRequest($request);
     
        if($form->isSubmitted() && $form->isValid()) {
            
            $article->setCreatedAt(new DateTime());
            
            // On récupère le champ de l'image et l'attribue à la variable $imageField
            $imageField = $form->get('image')->getData();   
            
            
            if ($imageField) {
                $fichier = md5(uniqid()) . '.' . $imageField->guessExtension();
                
                $imageField->move(
                    $this->getParameter('images_directory'),
                    $fichier, 
                );

                // On insère dans le setter setArticle de l'entité Picture le champ saisi (à savoir le nom du fichier uploadé)
                $image->setName($fichier);
                
                $article->setImage($image);
            }


            $article->setTitle($form->get('title')->getData()); 
            
            $user = $this->getUser();
            $article->setUserid($user);
            
            $slug = $articleSlugger->slugify($form->get('title')->getData());
            $article->setSlug($slug);

            $em = $this->getDoctrine()->getManager();
            $em->persist($article);
            //$em->persist($image);
            $em->flush();

            $this->addFlash('success', 'L\'article a bien été crée');

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

        //dd($article->getImage());
        $image = new Picture();
        
        $form = $this->createForm(EditArticleType::class, $article);
        
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            // On récupère le champ de l'image et l'attribue à la variable $imageField
            $imageField = $form->get('image')->getData();             
            
            // Si une image est insérée dans le champ
            if ($imageField) {

                // On vérifie si une précédente image est bien présente.
                // Si c'est le cas, on supprime cette image et on la remplace par la nouvelle
                if ($article->getImage() !== null) {

                    unlink($this->getParameter('images_directory').'/'.$article->getImage()->getName()); 

                    $fichier = md5(uniqid()) . '.' . $imageField->guessExtension();
                    
                    $imageField->move(
                        $this->getParameter('images_directory'),
                        $fichier, 
                    );

                }

                // On insère dans le setter setArticle de l'entité Picture le champ saisi (à savoir le nom du fichier uploadé)
                $image->setName($fichier);
                
                $article->setImage($image);
            }
          
            $slug = $articleSlugger->slugify($form->get('title')->getData());
            $article->setSlug($slug);
            
            $article = $form->getData();

            $em = $this->getDoctrine()->getManager();
            $em->flush();

            $this->addFlash('success', 'L\'article a bien été modifié');

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
       
            
            // Si l'article contient deja une image, dans ce cas là on supprime également le fichier du dossier uploads
            if ($article->getImage() !== null) {
                
                unlink($this->getParameter('images_directory').'/'.$article->getImage()->getName()); 
                
            }
            
            $em = $this->getDoctrine()->getManager();          
            $em->remove($article);
            $em->flush();

            $this->addFlash('success', 'L\'article a bien été supprimé');

            return $this->redirectToRoute('admin_home');
        }
       
    }

    /**
     * @Route("/image/{id}", name="delete_image")
     */
    public function deleteImage(Picture $picture, ArticleRepository $articleRepository, Request $request, CsrfTokenManagerInterface $csrfTokenManagerInterface): Response
    {      
        $em = $this->getDoctrine()->getManager();

        // On définit la propriété image du premier objet de articles à "NULL" ( il ne peut y en avoir qu'un seul)
        $articles = $articleRepository->findBy(['image' => $picture]);
        $articles[0]->setImage(null);
        
        // Puis on persiste
        $em->persist($articles[0]);

        // On récupère le nom de l'image
        $imageName = $picture->getName();

        // On supprime le fichier
        unlink($this->getParameter('images_directory').'/'.$imageName);

        // On supprime l'entrée de la base
        $em->remove($picture);
        $em->flush();
        
        $this->addFlash('success', 'L\'image a bien été supprimée');

        return $this->redirectToRoute('admin_home');
    }

}

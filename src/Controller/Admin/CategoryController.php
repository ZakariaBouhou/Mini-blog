<?php

namespace App\Controller\Admin;

use App\Entity\Category;
use App\Form\CategoryType;
use App\Repository\CategoryRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Csrf\CsrfToken;
use Symfony\Component\Security\Csrf\CsrfTokenManagerInterface;

/**
 * @Route("/admin/category", name="admin_category_")
 */
class CategoryController extends AbstractController

{
    /**
     * @Route("/add", name="add")
     */
    public function add(Request $request, EntityManagerInterface $em, CategoryRepository $categoryRepository): Response
    {
        $category = new Category();

        $categories = $categoryRepository->findAll();
        //dd($categories);

        $form = $this->createForm(CategoryType::class, $category);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {
                     
            $categoryName = $form->get('name')->getData();             
            
            $category->setName($categoryName); 

            $em = $this->getDoctrine()->getManager();
            $em->persist($category);

            $em->flush();

            $this->addFlash('success', 'Une nouvelle catégorie a été ajoutée');

            return $this->redirectToRoute('admin_category_add');
                            
            
        }

        return $this->render('category/add.html.twig', [

            'form' => $form->createView(),
            'categories' => $categories

        ]);
    }

    /**
     * @Route("/delete/{id}", name="delete")
     */
    public function delete(Category $category, Request $request, CsrfTokenManagerInterface $CsrfTokenManagerInterface): Response
    {       

        $token = new CsrfToken('deleteCategory', $request->query->get('_csrf_token'));
        
        if ($CsrfTokenManagerInterface->isTokenValid($token)) {      
                     
            
            $em = $this->getDoctrine()->getManager();          
            $em->remove($category);
            $em->flush();

            $this->addFlash('success', 'La catégorie a bien été supprimée');

            return $this->redirectToRoute('admin_category_add');
        }
       
    }
}

<?php

namespace App\Controller\Admin;

use App\Entity\Category;
use App\Form\CategoryType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/admin/category", name="admin_category_")
 */
class CategoryController extends AbstractController

{
    /**
     * @Route("/add", name="add")
     */
    public function add(Request $request, EntityManagerInterface $em): Response
    {
        $category = new Category();

        $form = $this->createForm(CategoryType::class, $category);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {
                     
            $categoryName = $form->get('name')->getData();   
            
            $category->setName($categoryName); 
                           
            $em = $this->getDoctrine()->getManager();
            $em->persist($category);

            $em->flush();

            return $this->redirectToRoute('admin_home');
        }

        return $this->render('category/add.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}

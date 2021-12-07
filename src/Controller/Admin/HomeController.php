<?php

namespace App\Controller\Admin;

use App\Repository\ArticleRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


class HomeController extends AbstractController
{
    /**
     * @Route("/admin/home", name="admin_home")
     */
    public function browse(ArticleRepository $articleRepository): Response
    {
        $allArticle = $articleRepository->findAll();

        return $this->render('admin/home.html.twig', [
            'articles' => $allArticle,
        ]);
    }

}

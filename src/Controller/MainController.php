<?php

namespace App\Controller;

use App\Repository\ArticleRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MainController extends AbstractController
{
    /**
     * @Route("/", name="main")
     */
    public function browse(ArticleRepository $articleRepository): Response
    {

        $articlesWithTheirCategory = $articleRepository->test();
        $allArticle = $articleRepository->findAll();

        return $this->render('main/browse.html.twig', [
            'articles' => $allArticle,
            'allArticles' => $articlesWithTheirCategory
        ]);
    }
}

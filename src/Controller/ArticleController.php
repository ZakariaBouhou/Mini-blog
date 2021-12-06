<?php

namespace App\Controller;

use App\Repository\ArticleRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ArticleController extends AbstractController
{
    /**
     * @Route("/article/{id}", name="read", requirements={"id"="\d+"})
     */
    public function read(ArticleRepository $articleRepository, int $id): Response
    {
        $oneArticle = $articleRepository->oneArticleWithHerCategory($id);
        
        return $this->render('article/read.html.twig', [
            'article' => $oneArticle
        ]);
    }
}

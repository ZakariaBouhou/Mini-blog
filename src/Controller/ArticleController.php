<?php

namespace App\Controller;

use App\Entity\Article;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ArticleController extends AbstractController
{
    /**
     * @Route("/article/{slug}", name="read_slug")
     */
    public function readSlug(Article $article): Response
    {        
        return $this->render('article/read.html.twig', [
            'article' => $article,
        ]);
    }
}

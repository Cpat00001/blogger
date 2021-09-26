<?php

namespace App\Controller;

use App\Entity\Article;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\ArticleRepository;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ArticleController extends AbstractController
{
    /**
     * @Route("/showarticles", name="show_articles")
     */
    public function showarticles( ArticleRepository $articleRepository): Response
    {
        $repository = $this->getDoctrine()->getRepository(Article::class);
        $articles = $repository->findAll(); 
        //var_dump($articles);
        return $this->render('article/listArticles.html.twig', [
            'articles' => $articles,
        ]);
    }
     /**
     * @Route("/newarticle", name="create_article")
     */
    public function create(){
        //fetch entityManager using $this->getDoctrine()
        $entityManager = $this->getDoctrine()->getManager();

        $article = new Article();
        $article->setName('Article Three');
        $article->setDescription('some long text here for article one');
        $article->setAuthor('Adam Lot');
        $article->setImage('images/img3.jpg');
        $article->setCreated(new \DateTime());

        // tell Doctrine you want to (eventually) save the Product (no queries yet)
        $entityManager->persist($article);
        // actually executes the queries (i.e. the INSERT query)
        $entityManager->flush();

        return new Response('Saved new article with id '.$article->getId());
    }
     /**
     * @Route("/blogpost/{id}/{slug}", name="showIndividual")
     */
    public function showInd(int $id , string $slug){
        $repository =  $this->getDoctrine()->getRepository(Article::class);
        // search article by unique ID and Title/Name
        $article = $repository->findOneBy([
            'id' => $id,
            'name' => $slug
        ]); 
        // return new Response('Requested article: ' . $article->getId() . ' and name: ' . $article->getName());
        return $this->render('article/individual.html.twig',[
            'article' => $article,
        ]);
    }
}

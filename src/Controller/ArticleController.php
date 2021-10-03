<?php

namespace App\Controller;

// dla formy komentarza 
use App\Entity\Comment;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

use App\Entity\User;
use Symfony\Component\Security\Core\User\UserInterface;
// koniec formy komentarza

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
    public function showInd(int $id , string $slug , Request $request){
        $repository =  $this->getDoctrine()->getRepository(Article::class);
        // search article by unique ID and Title/Name
        $article = $repository->findOneBy([
            'id' => $id,
            'name' => $slug
        ]);
        //get article ID
        $articleID = $article->getId();
        //dd($articleID);
        // insert comment form + proceding from commentController
             //fetch EntityManager via $this->getDoctrine();
        $entityManager = $this->getDoctrine()->getManager();
        //check if user is logged, 
        // if not set username to annonymous and dont show comment form insted login page link
        $user = $this->getUser();
        if($user == NULL){
            $currentUsername = 'annonymous';
        }else{
            $currentUsername = $user->getUserIdentifier();
        }
        //var_dump($currentUsername);      
        $comment = new Comment();
        //insert current date of created comment
        $comment->setAdded(new \DateTime());
        $comment->setUsername($currentUsername);
        $comment->setArticleID($articleID);
        
        //build a form
        $form = $this->createFormBuilder($comment)
            ->add('comment', TextareaType::class, [
                'attr' => ['style' => 'height: 200px; width: 80%',  'required' => true,]
            ])
            ->add('username', TextType::class,[
                'data' => $currentUsername,
                'disabled' => true,
            ])
            ->add('Add_Comment', SubmitType::class,)
            ->getForm();
        
        //var_dump($form);
        //processing form
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){

             // $form->getData() holds the submitted values
            // but, the original `$comment` variable has also been updated
            $comment = $form->getData();
            //var_dump($comment);
            $entityManager->persist($comment);
            $entityManager->flush();
            
            //redirect to current page + show flash message
            $this->addFlash('success', 'Your comment has been added');
            //don not redirect after form submitting 
            //show only FlashMessage and stay on the same individual article
        }

        //@@@@@@@@@@@@@@@@@@@@ end of insertet commentForm @@@@@@@@@@@@@@@@@@@@
        //show all comments for a particular articleID
        $comments = $this->getDoctrine()
                    ->getRepository(Comment::class)
                    ->findBy([
                        'articleID' => $articleID
                    ]);
        // dd($comments);
        // return new Response('Requested article: ' . $article->getId() . ' and name: ' . $article->getName());
        return $this->render('article/individual.html.twig',[
            'article' => $article,
            'comments' => $comments,
        ]);
    }
}

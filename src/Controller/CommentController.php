<?php

namespace App\Controller;

use App\Entity\Comment;
use App\Entity\Article;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

use App\Entity\User;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Http\Util\TargetPathTrait;


use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CommentController extends AbstractController
{
      //edit comment
     /**
     * @Route("/comment/edit/{id}", name="comment_edit")
     */ 
    public function showInd(int $id , Request $request, UserInterface $user): Response
    {
        $comment = $this->getDoctrine()
                   ->getRepository(Comment::class)
                   ->find($id);
        if(!$comment){
            throw $this->createNotFoundException(
                'No comment found with ' .$id
            );
        }
        $currentUsername = $user->getUserIdentifier();
    // dd($comment->getId());
    $cId = $comment->getId();
        // //build a form
        $form = $this->createFormBuilder($comment)
            ->add('comment', TextareaType::class, [
                'attr' => ['style' => 'height: 200px; width: 80%',  'required' => true,]
            ])
            ->add('username', TextType::class,[
                'data' => $currentUsername,
                'disabled' => true,
            ])
            ->add('Save_changes', SubmitType::class,[
                'attr' => ['style' => 'width:100%;']
            ])
            // ->add('Delete_Comment', SubmitType::class,[
            //     'attr' => ['style' => 'background-color:#dc3545;width:100%;'],
            // ])
            ->getForm();

            //connect to DB + proceed form and passed data
            $entityManager = $this->getDoctrine()->getManager();
            $form->handleRequest($request);
            if($form->isSubmitted()&& $form->isValid()){
                $form->getData();
                $comment = $form->getData();

                // var_dump($comment);
                $entityManager->persist($comment);
                $entityManager->flush();

                //show confirmation msg
                $this->addFlash('success','Your comment changes have been saved');
            }
            

            
        // return new Response('Change comment and save changes : ' .$comment->getComment());
        return $this->render('comment/editComment.html.twig',[
            // 'comment_txt' => $comment->getComment(),
            'form' => $form->createView(),
            'comment' => $comment,
        ]);
    }
    /**
    * @Route("/comment", name="comment")
    */
    public function commentForm(Request $request, UserInterface $user): Response
    {
        // //fetch EntityManager via $this->getDoctrine();
        // $entityManager = $this->getDoctrine()->getManager();
        // //$currentUser = $user;
        // //var_dump($currentUser);
        $currentUsername = $user->getUserIdentifier();
        // //var_dump($currentUsername);      
        $comment = new Comment();
        // //insert current date of created comment
        $comment->setAdded(new \DateTime());
        $comment->setUsername($currentUsername);
        
        // //build a form
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
        
        // $commentForm = "Comment Form";
        return $this->render('comment/commentForm.html.twig', [
            'comment' => $comment,
            'form' => $form->createView(),
        ]);

        // return new Response('dzieki za comment');
    }
    // delete comment from DB
    /**
     * @Route("comment/delete/{id}", name="delete_comment")
     */
    public function deleteComment( int $id ):Response
    {
        $entityManager = $this->getDoctrine()->getManager();
        $comment = $entityManager->getRepository(Comment::class)->find($id);
        var_dump($comment);
        $commentID = $comment->getId();
        $entityManager->remove($comment);
        echo "</br></br></br>";
        $articleId = $comment->getArticleID();
        //var_dump($articleId);
        $currentArticle = $entityManager->getRepository(Article::class)->find($articleId);
        //var_dump($currentArticle);
        //echo "</br></br></br>";
        $currentArticleSlug = $currentArticle->getName();
        //var_dump("Current Article Slug " . $currentArticleSlug);
        //execute query to DB and DELETE comment 
        $entityManager->flush();
        //show confirmation message
        $this->addFlash('deleted','Your comment was DELETED sucessfully');
        //redirect to IndividualArticle with list of comments minus deleted one
        return $this->redirectToRoute('showIndividual', array('id' => $articleId , 'slug' => $currentArticleSlug));
       
    }
    
}

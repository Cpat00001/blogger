<?php

namespace App\Controller;

use App\Entity\Comment;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\Extension\Core\Type\DateType;
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
    /**
    * @Route("/comment", name="comment")
    */
    public function commentForm(Request $request, UserInterface $user): Response
    {
        //$currentUser = $user;
        //var_dump($currentUser);
        $currentUsername = $user->getUserIdentifier();
        //var_dump($currentUsername);      
        $comment = new Comment();
        
        //build a form
        $form = $this->createFormBuilder($comment)
            ->add('comment', TextareaType::class, [
                'attr' => ['style' => 'height: 200px; width: 80%',  'required' => true,]
            ])
            ->add('username', TextType::class,[
                'data' => $currentUsername,
                'disabled' => true,
            ])
            // ->add('added', DateType::class)
            ->add('Add_Comment', SubmitType::class,)
            ->getForm();
        //var_dump($form);
        //processing form
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){

            echo "<h1>Submitted</h1>";
             // $form->getData() holds the submitted values
            // but, the original `$comment` variable has also been updated
            $comment = $form->getData();
             //insert current date of created comment
             $comment->setAdded(new \DateTime());
             $comment->setUsername($currentUsername);
             var_dump($comment);

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($comment);
            $entityManager->flush();
            
            //redirect to current page + show flash message
            $this->addFlash('success', 'Your comment has been added');
            //redirect
            //return $this->redirectToRoute('welcome');
        }

        $commentForm = "Comment Form";
        return $this->render('comment/commentForm.html.twig', [
            'commentForm' => $commentForm,
            'form' => $form->createView(),
        ]);
    }
}

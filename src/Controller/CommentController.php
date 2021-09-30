<?php

namespace App\Controller;

use App\Entity\Comment;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;

use App\Entity\User;
use App\Repository\UserRepository;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CommentController extends AbstractController
{
    /**
    * @Route("/comment", name="comment")
    */
    public function commentForm(UserRepository $userRepository, Request $request): Response
    {
        $repository = $this->getDoctrine()->getRepository(User::class);
        $users = $repository->findAll();


        $comment = new Comment();

        $form = $this->createFormBuilder($comment)
            ->add('comment', TextType::class, [
                'attr' => ['style' => 'height: 200px', 'required' => true,]
            ])
            ->add('username', TextType::class,[
                'data' => 'session user',
                'disabled' => true,
            ])
            // ->add('added', DateType::class)
            ->add('submit', SubmitType::class,[
                'attr' => ['class' => 'Add Comment']
            ])
           ->getForm();
        //var_dump($form);

        $commentForm = "Comment Form";
        return $this->render('comment/commentForm.html.twig', [
            'commentForm' => $commentForm,
            'form' => $form->createView(),
            'users' => $users,
            //'username' => $username,
            // 'com' => $comment,
        ]);
    }
}

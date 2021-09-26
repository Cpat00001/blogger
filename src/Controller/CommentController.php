<?php

namespace App\Controller;

use App\Entity\Comment;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CommentController extends AbstractController
{
    /**
    * @Route("/comment", name="comment")
    */
    public function commentForm(Request $request): Response
    {
        $comment = new Comment();

        $form = $this->createFormBuilder($comment)
            ->add('comment', TextType::class)
            ->add('username', TextType::class)
            ->add('added', DateType::class)
            ->add('submit', SubmitType::class,[
                'attr' => ['class' => 'Add Comment']
            ])
           ->getForm();

        $commentForm = "Join the discussion, add your opinion";
        return $this->render('comment/commentForm.html.twig', [
            'commentForm' => $commentForm,
            'form' => $form,
        ]);
    }
    // convert Object to string
    // public function __toString()
    // {
    //     return $this->getForm();
    // }
}

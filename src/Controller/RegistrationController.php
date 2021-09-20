<?php

namespace App\Controller;

use App\Entity\User;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

use Doctrine\ORM\EntityManagerInterface;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class RegistrationController extends AbstractController
{
    /**
     * @Route("/registration", name="registration")
     */
    public function register (Request $request): Response
    {
        $user = new User();

        $form = $this->createFormBuilder($user)
                ->add('username' , TextType::class)
                ->add('email', EmailType::class)
                ->add('password', RepeatedType::class, [
                    'type' => PasswordType::class,
                    'invalid_message' => 'The Password fields must match',
                    'options' => ['attr' => ['class' => 'password-field']],
                    'required' => true,
                    'first_options' => ['label' => 'Password'],
                    'second_options' => ['label' => 'Repeat Password']
                ])
                ->add('submit', SubmitType::class,[
                    'attr' => ['class' => 'Register']
                ])
                ->getForm();

                $form->handleRequest($request);
                if($form->isSubmitted() && $form->isValid()){
                    // $form->getData() holds the submitted values
                    $user = $form->getData();
                    // save user into DB
                    $entityManager = $this->getDoctrine()->getManager();
                    $entityManager->persist($user);
                    //execute the query (INSERT INTO)
                    $entityManager->flush();

                    //redirect to HomePage and show flashMessage with confirmation 
                    $this->addFlash(
                        'registered',
                        'You have been registered => you can LOGIN now' 
                    );
                    return $this->redirectToRoute('welcome');
                }
        
        $title = 'Registration Form';

        return $this->render('registration/registrationForm.html.twig', [
            'form' => $form->createView(),
            'title' => $title,
        ]);
    }
}

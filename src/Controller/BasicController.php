<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class BasicController extends AbstractController
{
    /**
     * @Route("/", name="welcome")
     */
    public function index(): Response
    {
        $title = "Welcome Page";
        return $this->render('basic/index.html.twig', [
            'page_name' => $title,
        ]);
    }
    // contact page
    /**
     * @Route("/contact", name="contact")
     */

    public function contact():Response
    {
        $title = "Contact Page";
        $name = "John Tester";
        $email = "john@tester.gmail.com";
        $phone = "123-456-789";

        return $this->render('basic/contact.html.twig' , [
            'title' => $title,
            'name' => $name,
            'email' => $email,
            'phone' => $phone,
        ]);
    }
}

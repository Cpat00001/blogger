<?php

namespace App\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;

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
     // aboutme page
    /**
     * @Route("/logined", name="logined")
     */
    public function logined(){

        $title = "Welcome Logined";

        return $this->render('basic/aboutme.html.twig' , [
            'title' => $title,
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
    // aboutme page
    /**
     * @Route("/aboutme", name="aboutme")
     */
    public function aboutme(){

        $title = "About Me";

        return $this->render('basic/aboutme.html.twig' , [
            'title' => $title,
        ]);
    }
     // restricted
    /**
     * @Route("/restricted", name="restricted_content")
     * @IsGranted("ROLE_USER")
     */

    public function restrictedContent(){
        
        $title = 'VIP Content';

        return $this->render('basic/restricted_content.html.twig',[
            'title' => $title,
        ]);
    }
}

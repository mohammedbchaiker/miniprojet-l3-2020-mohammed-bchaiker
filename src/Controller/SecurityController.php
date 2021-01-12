<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\RegistartionType;
use DateTime;
use Doctrine\Persistence\ObjectManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class SecurityController extends AbstractController
{
    /**
     * @Route("/register", name="registartion")
     * @param Request $request
     * @param ObjectManager $manager
     * @param UserPasswordEncoderInterface $passwordEncoder
     * @return Response
     */
    public function register(Request $request, ObjectManager $manager, UserPasswordEncoderInterface $passwordEncoder): Response
    {
        $user=new User();
        $form=$this->createForm(RegistartionType::class, $user);
        $view=$form->createView();
        $form->handleRequest($request);
        if ($form->isSubmitted()){

            $captcha=$_POST["g-recaptcha-response"];
            $secretkey="6LeugSUaAAAAAP4qTVHWDSyN32s51zmczs7gHX2I";
            $url="https://www.google.com/recaptcha/api/siteverify?secret=".urlencode($secretkey)."&response=".urlencode($captcha)." ";
            $response=file_get_contents($url);
            $responsekey=  json_decode($response,TRUE);
            if ($responsekey['success'] && $form->isValid()){
                $var = new DateTime();
                $var = $var->getTimestamp();
                $hash = $passwordEncoder->encodePassword($user,$user->getPassword());
                $user->setPassword($hash);
                $manager->persist($user);
                $manager->flush();
               return $this->redirectToRoute('login');
            }}




        return $this->render('security/register.html.twig', [
            'view' => $view,

        ]);


    }

    /**
     * @Route("/login", name="login")
    */
    public function login(): Response
    {
        return $this->render('security/login.html.twig');
    }


    /**
    @Route("/logout", name="logout")
     */
    public function logout(){}

    public function mailConfirmation(String $email,String $content){

        $email = (new Email())
            ->from('mohammedbchaiker@gmail.com')
            ->to("$email")
            ->priority(Email::PRIORITY_HIGH)
            ->subject('Confirmer votre inscription')

            ->html("<div><a href='http://localhost/registration/verification'></a></div>")
        ;
    }



}

<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\RegistartionType;
use App\Repository\DiscussionRepository;
use App\Repository\UserRepository;
use App\Security\UsersAuthenticator;
use DateTime;
use Doctrine\Persistence\ObjectManager;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
//use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Guard\GuardAuthenticatorHandler;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use SymfonyCasts\Bundle\VerifyEmail\Exception\VerifyEmailExceptionInterface;
use SymfonyCasts\Bundle\VerifyEmail\VerifyEmailHelperInterface;

class SecurityController extends AbstractController
{


    /**
     * @var VerifyEmailHelperInterface
     */




    /**
     * @Route("/register", name="registartion")
     * @param Request $request
     * @param ObjectManager $manager
     * @param UserPasswordEncoderInterface $passwordEncoder
     * @param \Swift_Mailer $mailer
     * @return Response
     */
    public function register(Request $request, ObjectManager $manager,\Swift_Mailer $mailer,UserPasswordEncoderInterface $passwordEncoder): Response
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

                $hash = $passwordEncoder->encodePassword($user,$user->getPassword());
                $vkey =new DateTime();
                $vkey = $vkey->getTimestamp();
                $user->setVkey($vkey);

                $user->setPassword($hash);
                $manager->persist($user);
                $manager->flush();
                // On crée le message
                $message = (new \Swift_Message('Bienvenue dans notre Site !'))
                    ->setFrom('bchaikermed@gmail.com')
                    ->setTo($user->getEmail())
                    ->setBody(
                        $this->renderView(
                            'emails/activation.html.twig', ['vkey' => $user->getVkey()]
                        ),
                        'text/html'
                    )
                ;
                $mailer->send($message);
            }}
        return $this->render('security/register.html.twig', [
            'view' => $view,

        ]);


    }

    /**
     * @Route("/login", name="login")
     * @return Response
     */
    public function login(): Response
    {
        $t=$this->getUser();

        if ($t->getVerified()==true){
            if ($t->getVkey()=="admin"){
                return $this->redirectToRoute('admin');
            }
            return $this->redirectToRoute('welcome');
        }
        if ($t->getVerified()==false){
            return $this->redirectToRoute('font');
        }
    }


    /**
    @Route("/logout", name="logout")
     */
    public function logout(){}


    /**
     * @Route("/verify/{vkey}", name="registration_confirmation_route")
     * @param $vkey
     * @param UserRepository $user
     * @return Response
     */

    public function activation($vkey, UserRepository $user)
    {
        $user = $user->findOneBy(['vkey' => $vkey]);

        if(!$user){
            throw $this->createNotFoundException('Cet utilisateur n\'existe pas');
        }
        $user->setVerified(true);
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($user);
        $entityManager->flush();

        // On génère un message
        $this->addFlash('message', 'Utilisateur activé avec succès');
        return $this->redirectToRoute('welcome');
    }


    /**
    @Route("/tototototot/", name="font")
     */

    public function font(){
        return $this->render('emails/non.html.twig');
    }



    /**
     @Route("/admin", name="admin")
    */
    public function admin(){
        
        return $this->render('discussion/index.html.twig');

    }



}

<?php

namespace App\Controller;

use App\Entity\Discussion;
use App\Entity\Theme;
use App\Form\DiscussionType;
use App\Form\ThemeType;
use App\Repository\DiscussionRepository;
use App\Repository\ThemeRepository;
use Doctrine\Persistence\ObjectManager;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ForumController extends AbstractController
{
    /**
     * @Route("/discussion", name="index_forum")
     * @param DiscussionRepository $discussionRepository
     * @return Response
     */
    public function index(DiscussionRepository $discussionRepository ): Response
    {
        $discussions = $discussionRepository->findAll();

        return $this->render('forum/index.html.twig', [
            'controller_name' => 'ForumController',
            'discussions'=>$discussions
        ]);
    }

    /**
     * @Route("/discussion/{id}", name="show_forum")
     * @param Discussion $discussion
     * @return Response
     */

    public function show(Discussion $discussion){
        return $this->render('forum/discussion.html.twig',['discussion'=>$discussion]);
    }


    /**
     * @Route ("/", name="welcome")
     * @param ThemeRepository $themeRepository
     * @param Request $request
     * @return Response
     */
    public function welcome(ThemeRepository $themeRepository, Request $request){
        $themes = $themeRepository->pagination((int)$request->query->get("page",1),5);

        return $this->render('forum/welcome.html.twig',['themes'=>$themes,
            'nbOfthemes'=>$themeRepository->countTheme()]);
    }


    /**
     * @Route ("theme/{id}", name="theme_discussion")
     * @param Theme $theme
     * @return Response
     */
    public function show_discussions(Theme $theme){
        $val = $theme->getDiscussions()->count();


        return $this->render('forum/content.html.twig',['theme'=>$theme,'val'=>$val]);
    }


    /**
     * @Route ("/creatediscussion", name="create_discussion")
     * @param Request $request
     * @param ObjectManager $manager
     * @return Response
     */


    public function create_discussion(Request $request, ObjectManager $manager){
       $discussion = new Discussion();
       $form = $this->createForm(DiscussionType::class,$discussion);
       $view = $form->createView();

       $form->handleRequest($request);

       if ($form->isSubmitted() && $form->isValid()){
           $discussion->setCreatedAt(new \DateTime());
           $manager->persist($discussion);
           $manager->flush();

           return $this->redirectToRoute('show_forum',['id'=>$discussion->getId()]);

       }
       return $this->render('forum/addDiscussion.html.twig',['view'=>$view]);
    }

    /**
     * @Route ("create_theme", name="create_theme")
    */


    public function create_theme(Request $request, ObjectManager $manager){
        $theme = new Theme();
        $form=$this->createForm(ThemeType::class,$theme);
        $view=$form->createView();
        $form->handleRequest($request);

            if ( $form->isSubmitted()&& $form->isValid()){
                $theme->setCreatedAt(new \DateTime());
                $manager->persist($theme);
                $manager->flush();
                return $this->redirectToRoute('theme_discussion',['id'=>$theme->getId()]);
            }

        return $this->render('forum/addTheme.html.twig',['view'=>$view]);

}


}

<?php

namespace App\Controller;

use App\Entity\Discussion;
use App\Entity\Theme;
use App\Repository\DiscussionRepository;
use App\Repository\ThemeRepository;
use Doctrine\Persistence\ObjectManager;
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
     * @return Response
     */
    public function welcome(ThemeRepository $themeRepository){
        $themes = $themeRepository->findAll();
        return $this->render('forum/welcome.html.twig',['themes'=>$themes]);
    }


    /**
     * @Route ("theme/{id}", name="theme_discussion")
     * @param Theme $theme
     * @return Response
     */
    public function show_discussions(Theme $theme){
        return $this->render('forum/content.html.twig',['theme'=>$theme]);
    }


    /**
     * @Route ("/creatediscussion", name="create_discussion")
     * @param Request $request
     */


    public function create_discussion(Request $request, ObjectManager $manager){

    }

}

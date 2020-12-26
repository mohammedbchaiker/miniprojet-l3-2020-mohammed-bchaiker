<?php

namespace App\Controller;

use App\Entity\Discussion;
use App\Repository\DiscussionRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
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
}

<?php

namespace App\Controller;

use App\Entity\Comment;
use App\Form\CommentType;
use App\Repository\CommentRepository;
use App\Repository\ConferenceRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/comment")
 */
class CommentController extends AbstractController
{
    /**
     * @Route("/{id}", name="comment_show", methods={"GET"})
     * @param Comment $comment
     * @return Response
     */
    public function show(Comment $comment): Response
    {
        return $this->render('comment/show.html.twig', [
            'comment'  => $comment,
        ]);
    }

    /**
     * @Route("/norated/{slug}", name="conference_norated")
     * @param CommentRepository $commentRepository
     * @param ConferenceRepository $conferenceRepository
     * @return Response
     */
    public function getNotRate(CommentRepository $commentRepository, ConferenceRepository $conferenceRepository)
    {
        $aCom           = $commentRepository->findAll();
        $aConf          = $conferenceRepository->findAll();
        $e = [];
        $r = [];
        foreach ($aConf as $item){
            $e[] = $item->getId();
        }
        foreach ($aCom as $item){
            $r[] = $item->getRefNoteId();
        }

        $f = array_unique(array_merge($e,$r));
        dump($f);exit;
        $maxPages = ceil(1);
        return $this->render('conference/search.html.twig', [
            'conferences' => $list,
            'maxPages' => $maxPages
        ]);
    }
}

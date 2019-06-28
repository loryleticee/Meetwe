<?php

namespace App\Controller;

use App\Entity\Comment;
use App\Form\CommentType;
use App\Repository\CommentRepository;
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
     * @return Response
     */
    public function getNotRate(CommentRepository $commentRepository)
    {
        $list           = $commentRepository->notRated();

        $maxPages = ceil(1);
        return $this->render('conference/search.html.twig', [
            'conferences' => $list,
            'maxPages' => $maxPages
        ]);
    }
}

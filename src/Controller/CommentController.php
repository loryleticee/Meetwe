<?php

namespace App\Controller;

use App\Entity\Comment;
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
    public function getNoRated(CommentRepository $commentRepository, ConferenceRepository $conferenceRepository)
    {
        $aCom               = $commentRepository->findAll();
        $aConf              = $conferenceRepository->findAll();

        $aConfIds           = [];
        $aConfIdsInComment  = [];

        $aNoRatedConf       = [];
        $result             = [];

        foreach ($aConf as $item) {
            $aConfIds[]     = $item->getId();
        }
        foreach ($aCom as $item) {
            $aConfIdsInComment[] = $item->getConference()->getId();
        }

        $reverse = array_flip($aConfIdsInComment);



        foreach ($aConfIds as $iConf) {
            if (!array_key_exists($iConf, $reverse)) {
                $aNoRatedConf [] = $iConf;
            }
        }

        foreach ($aNoRatedConf as $idConf) {
            $result [] = $conferenceRepository->find($idConf);
        }

        $maxPages = ceil(1);

        return $this->render('conference/norated.html.twig', [
            'conferences' => $result,
            'maxPages' => $maxPages
        ]);
    }

    /**
     * @Route("/confrated/{slug}", name="conference_rate")
     * @param CommentRepository $commentRepository
     * @param ConferenceRepository $conferenceRepository
     * @return Response
     */
    public function getRated(CommentRepository $commentRepository, ConferenceRepository $conferenceRepository)
    {
        $aCom               = $commentRepository->findAll();
        $result             = [];

        foreach ($aCom as $line) {
            $result [] = $conferenceRepository->find($line->getConference());
        }

        $maxPages = ceil(1);

        return $this->render('conference/rated.html.twig', [
            'conferences' => $result,
            'maxPages' => $maxPages
        ]);
    }

    /**
     * @Route("/addcomment", name="conference_comment", methods={"GET","POST"})
     * @param Request $request
     * @param ConferenceRepository $conferenceRepository
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     * @throws \Exception
     */
    public function comment(Request $request, ConferenceRepository $conferenceRepository)
    {
        $iConf          = $request->request->get('idConf');
        $conf           = $conferenceRepository->find($iConf);
        $sComment       = $request->request->get('text');
        $sRate          = (int)$request->request->get('rate');

        $comment        = new Comment();
        $comment->setPublishDate(new \DateTime());
        $comment->setUserId($this->getUser()->getId());
        $comment->setContent($sComment);
        $comment->setConference($conf);
        $comment->setRefNoteId($sRate);

        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($comment);
        $entityManager->flush();

        return $this->redirectToRoute('conference_show', ['id' => $iConf]);
    }
}

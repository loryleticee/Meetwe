<?php

namespace App\Controller;

use App\Entity\Comment;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use App\Manager\GameManager;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use App\Repository\ConferenceRepository;

class AjaxController extends AbstractController
{
    /**
     * @Route("/ajax/result", name="ajax_result")
     * @param Request $request
     * @return Response
     */
    public function result(Request $request)
    {
        $result = $request->request->get('result');
        $result = json_decode($result);
        return $this->render('ajax/result.html.twig', [
            'result' => $result,
        ]);
    }

    /**
     * @Route("/ajax/setnote", name="ajax_setnote")
     * @param Request $request
     * @param ConferenceRepository $conferenceRepository
     * @return Response
     * @throws \Exception
     */
    public function setNote(
        Request $request,
        ConferenceRepository $conferenceRepository
    ) {
        $sNote          = $request->get('note');
        $iConf          = $request->get('idconf');

        if (!$sNote || !$iConf) {
            return new Response('No note found', 300);
        }

        $conf           = $conferenceRepository->find($iConf);
        $manager        = new GameManager();
        $iNote          = $manager->getNote($sNote);
        $comment        = new Comment();
        $comment->setRefNoteId($iNote);
        $comment->setContent('');
        $comment->setConference($conf);
        $comment->setPublishDate(new \DateTime('now'));
        $comment->setUserId($this->getUser()->getId());
        $entityManager  = $this->getDoctrine()->getManager();
        $entityManager->persist($comment);
        $entityManager->flush();

        return new Response("$iNote");
    }
}

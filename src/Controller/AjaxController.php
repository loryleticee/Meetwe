<?php

namespace App\Controller;

use App\Entity\Comment;
use App\Repository\CommentRepository;
use App\Repository\RefNoteRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use App\Manager\GameManager;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use App\Repository\ConferenceRepository;

class AjaxController extends AbstractController
{
    /**
     * @Route("/ajax/active", name="ajax_active")
     * @param ConferenceRepository $articleRepository
     * @param Request $request
     * @return Response
     */
    public function active(ConferenceRepository $articleRepository, Request $request) : Response
    {
        $id         = $request->get('idarticle');
        if (!$id) {
            return new Response('No conference found', 300);
        }
        $article    = $articleRepository->find($id);

        $isCensored = $article->getCensored();
        $article->setCensored($isCensored === false ? true : false);
        $entityManager  = $this->getDoctrine()->getManager();
        $entityManager->persist($article);
        $entityManager->flush();

        return new Response("$isCensored");
    }

    /**
     * @Route("/ajax/mail", name="ajax_mail")
     * @param \Swift_Mailer $mailer
     * @return Response
     */
    public function sendMAil(\Swift_Mailer $mailer)
    {
        $message = (new \Swift_Message('Hello Email'))
            ->setFrom('loryleticee@gmail.com')
            ->setSubject('Votre dernier pari')
            ->setTo('loryleticee@gmail.com')
            ->setBody(
                $this->renderView(
                    'ajax/mail.html.twig',
                    ['name' => 'rvtveveveve']
                ),
                'text/plain'
            )
            //->addPart('', 'text/html')
        ;
        $mailer->send($message);

        return $this->render('game/play.html.twig', [
        ]);
    }

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
     * @param RefNoteRepository $refNoteRepository
     * @return Response
     * @throws \Exception
     */
    public function setNote(
        Request $request,
        ConferenceRepository $conferenceRepository,
        RefNoteRepository $refNoteRepository
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
        $refNote        = $refNoteRepository->find($iNote);
        $comment->setRefNoteId($refNote);
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

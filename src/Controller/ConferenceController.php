<?php

namespace App\Controller;

use App\Entity\Conference;
use App\Form\ConferenceType;
use App\Repository\CommentRepository;
use App\Repository\ConferenceRepository;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/index")
 */
class ConferenceController extends AbstractController
{
    /**
     * @Route("/", name="conference_index", methods={"GET"})
     * @param Request $request
     * @param ConferenceRepository $conferenceRepository
     * @return Response
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function index(Request $request, ConferenceRepository $conferenceRepository): Response
    {
        $page = $request->query->get('page') ?: 1;

        $conferences    = $conferenceRepository->orderconference($page);

        $totalPosts     = $conferenceRepository->nbrconference();

        $maxPages = ceil($totalPosts / 10);


        return $this->render('conference/index.html.twig', [
            'conferences' => $conferences,
            'maxPages' => $maxPages,
        ]);
    }

    /**
     * @Route("/{id}", name="conference_show", methods={"GET"})
     * @param Conference $conference
     * @param CommentRepository $commentRepository
     * @return Response
     */
    public function show(Conference $conference, CommentRepository $commentRepository): Response
    {
        $comments   = $commentRepository->getComments($conference->getId());
        $iAvg       = $commentRepository->getAvg($conference->getId());

        return $this->render('conference/show.html.twig', [
            'conference'    => $conference,
            'comments'      => $comments,
            'avg'           => $iAvg,
        ]);
    }
    /**
     * @Route("/new/{slug}", name="conference_new", methods={"GET","POST"})
     * @param Request $request
     * @param \Swift_Mailer $mailer
     * @param UserRepository $userRepository
     * @return Response
     * @throws \Exception
     */
    public function new(Request $request, \Swift_Mailer $mailer, UserRepository $userRepository): Response
    {
        /** @var Conference $conference */
        $conference = new Conference();
        $form = $this->createForm(ConferenceType::class, $conference);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $conference->setPublishDate(new \DateTime());
            $entityManager->persist($conference);
            $entityManager->flush();

            $aMails     = [];
            $aUsersMail = $userRepository->getMails();

            foreach ($aUsersMail as $aMail) {
                $aMails[]   = $aMail['mail'];
            }


            $message = (new \Swift_Message('Hello Email'))
                ->setFrom('loryleticee@gmail.com')
                ->setSubject('Découvrez notre prochaine conférence')
                ->setTo($aMails)
                ->setBody(
                    $this->renderView(
                        'send_mail/mail.html.twig',
                        [
                            'conference' => $conference
                        ]
                    ),
                    'text/html'
                );
            $mailer->send($message);

            return $this->redirectToRoute('conference_index');
        }

        return $this->render('conference/new.html.twig', [
            'conference' => $conference,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}/edit", name="conference_edit", methods={"GET","POST"})
     * @param Request $request
     * @param Conference $conference
     * @return Response
     */
    public function edit(Request $request, Conference $conference): Response
    {
        $form = $this->createForm(ConferenceType::class, $conference);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('conference_index', [
                'id' => $conference->getId(),
            ]);
        }

        return $this->render('conference/edit.html.twig', [
            'conference' => $conference,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="conference_tui", methods={"DELETE"})
     * @param Request $request
     * @param Conference $conference
     * @param CommentRepository $commentRepository
     * @return Response
     */
    public function delete(Request $request, Conference $conference, CommentRepository $commentRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$conference->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($conference);
            $entityManager->flush();
        }

        return $this->redirectToRoute('conference_index');
    }

    /**
     * @Route("/topten/{slug}", name="conference_topten", methods={"GET"})
     * @param CommentRepository $commentRepository
     * @return Response
     */
    public function topTen(CommentRepository $commentRepository)
    {
        $aConf = $commentRepository->getTopTen();

        return $this->render('conference/topten.html.twig', [
            'toptens' => $aConf,
        ]);
    }

    /**
     * @Route("/search/{slug}", name="conference_search", methods={"GET","POST"})
     * @param Request $request
     * @param ConferenceRepository $conferenceRepository
     * @return Response
     */
    public function search(Request $request, ConferenceRepository $conferenceRepository)
    {

        $text           = $request->get('text');
        $list           = $conferenceRepository->search($text);

        $maxPages = ceil(1);
        return $this->render('conference/search.html.twig', [
            'conferences' => $list,
            'maxPages' => $maxPages
        ]);
    }
}

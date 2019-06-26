<?php

namespace App\Controller;


use App\Entity\Comment;
use App\Entity\Conference;
use App\Form\ConferenceType;
use App\Repository\ConferenceRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Form\CommentType;
use Exception;

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
        if ($this->getUser()) {
            $isAdmin = in_array('ROLE_ADMIN', $this->getUser()->getRoles());
        } else {
            $isAdmin = false;
        }
        $conferences = $isAdmin === true ?
            $conferenceRepository->orderconferenceAdmin($page) :
            $conferenceRepository->orderconference($page);

        $totalPosts = $isAdmin === true ?
            $conferenceRepository->nbrconferenceAdmin() :
            $conferenceRepository->nbrconference();

        $maxPages = ceil($totalPosts / 10);

        return $this->render('conference/index.html.twig', [
            'conferences' => $conferences,
            'maxPages' => $maxPages,
        ]);
    }

    /**
     *
     * @Route("/{id}", name="conference_show")
     * @param Conference $conference
     * @param Request $request
     * @return Response
     * @throws \Exception

    public function show(Conference $conference, Request $request): Response
    {
        if ($conference->getCensored() === false) {
            $form = $this->createForm(CommentType::class);
            $form->handleRequest($request);
            if ($form->isSubmitted() && $form->isValid()) {
                $data = $form->getData();

                if (!$this->getUser() && !$data['username']) {
                    return $this->redirectToRoute('conference_index');
                }

                $comment = new Comment();
                $comment->setContent($data['content']);
                $comment->setPublishDate(new \DateTime('now'));
                $comment->setCensored(false);
                $comment->setUsername($this->getUser() ? $this->getUser()->getUsername() : $data['username']);
                $comment->setconference($conference);

                $entityManager = $this->getDoctrine()->getManager();
                $entityManager->persist($conference);
                $entityManager->persist($comment);
                $entityManager->flush();

                try {
                    $entityManager->flush();
                } catch (Exception $e) {
                    echo 'Caught exception: ', $e->getMessage(), "\n";
                }
            }
        }
        return $this->render('conference/show.html.twig', [
            'conference' => $conference,
            'comments' => $conference->getCensored() === false ? $conference->getComments() : '',
            'form' => isset($form) ? $form->createView() : '',
        ]);
    }
    **/


    /**
     * @Route("/{id}", name="conference_show", methods={"GET"})
     * @param Conference $conference
     * @return Response
     */
    public function show(Conference $conference): Response
    {
        $comments = $conference->getComments();
        return $this->render('conference/show.html.twig', [
            'conference' => $conference,
            'comments'=> $comments,
        ]);
    }
    /**
     * @Route("/new/{slug}", name="conference_new", methods={"GET","POST"})
     * @param Request $request
     * @return Response
     */
    public function new(Request $request): Response
    {
        /** @var Conference $conference */
        $conference = new Conference();
        $form = $this->createForm(ConferenceType::class, $conference);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($conference);
            $entityManager->flush();

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
     * @param Request $request
     * @param Conference $conference
     * @return Response
     */
    public function delete(Request $request, Conference $conference): Response
    {
        if ($this->isCsrfTokenValid('delete'.$conference->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($conference);
            $entityManager->flush();
        }

        return $this->redirectToRoute('conference_index');
    }


}





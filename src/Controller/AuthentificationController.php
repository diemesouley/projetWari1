<?php

namespace App\Controller;

use App\Entity\Authentification;
use App\Form\AuthentificationType;
use App\Repository\AuthentificationRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/api/authentification")
 */
class AuthentificationController extends AbstractController
{
    /**
     * @Route("/", name="authentification_index", methods={"GET"})
     */
    public function index(AuthentificationRepository $authentificationRepository): Response
    {
        return $this->render('authentification/index.html.twig', [
            'authentifications' => $authentificationRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="authentification_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $authentification = new Authentification();
        $form = $this->createForm(AuthentificationType::class, $authentification);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($authentification);
            $entityManager->flush();

            return $this->redirectToRoute('authentification_index');
        }

        return $this->render('authentification/new.html.twig', [
            'authentification' => $authentification,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="authentification_show", methods={"GET"})
     */
    public function show(Authentification $authentification): Response
    {
        return $this->render('authentification/show.html.twig', [
            'authentification' => $authentification,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="authentification_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Authentification $authentification): Response
    {
        $form = $this->createForm(AuthentificationType::class, $authentification);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('authentification_index');
        }

        return $this->render('authentification/edit.html.twig', [
            'authentification' => $authentification,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="authentification_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Authentification $authentification): Response
    {
        if ($this->isCsrfTokenValid('delete'.$authentification->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($authentification);
            $entityManager->flush();
        }

        return $this->redirectToRoute('authentification_index');
    }
}

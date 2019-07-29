<?php

namespace App\Controller;

use App\Entity\Administrateur;
use App\Form\AdministrateurType;
use App\Repository\AdministrateurRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Doctrine\ORM\EntityManagerInterface;


/**
 * @Route("/api/administrateur")
 * @IsGranted("ROLE_PARTENAIRE") 
 */
class AdministrateurController extends AbstractController
{
    /**
     * @Route("/", name="administrateur_index", methods={"GET"})
     */
    public function index(AdministrateurRepository $administrateurRepository): Response
    {
        return $this->render('administrateur/index.html.twig', [
            'administrateurs' => $administrateurRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="administrateurnew", methods={"GET","POST"})
     */
    public function ajout(Request $request,SerializerInterface $serializer, EntityManagerInterface $entityManager): Response
    {
        $administrateur = new Administrateur();
        $form=$this->createForm(AdministrateurType::class,$administrateur);
        $data=json_decode($request->getContent(),true);
        //var_dump($administrateur); die();
        $form->submit($data);
        
            $entityManager=$this->getDoctrine()->getManager();
            $entityManager->persist($administrateur);
            $entityManager->flush();
            return new Response('Administrateur ajouter', Response::HTTP_CREATED);

    }

    /**
     * @Route("/{id}", name="administrateur_show", methods={"GET"})
     */
    public function show(Administrateur $administrateur): Response
    {
        return $this->render('administrateur/show.html.twig', [
            'administrateur' => $administrateur,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="administrateur_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Administrateur $administrateur): Response
    {
        $form = $this->createForm(AdministrateurType::class, $administrateur);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('administrateur_index');
        }

        return $this->render('administrateur/edit.html.twig', [
            'administrateur' => $administrateur,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="administrateur_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Administrateur $administrateur): Response
    {
        if ($this->isCsrfTokenValid('delete'.$administrateur->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($administrateur);
            $entityManager->flush();
        }

        return $this->redirectToRoute('administrateur_index');
    }
}

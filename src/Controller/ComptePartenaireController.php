<?php

namespace App\Controller;

use App\Entity\ComptePartenaire;
use App\Form\ComptePartenaireType;
use App\Repository\ComptePartenaireRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Doctrine\ORM\EntityManagerInterface;


/**
 * @Route("/api/compte/partenaire")
 */
class ComptePartenaireController extends AbstractController
{
    /**
     * @Route("/", name="compte_partenaire_index", methods={"GET"})
     */
    public function index(ComptePartenaireRepository $comptePartenaireRepository): Response
    {
        return $this->render('compte_partenaire/index.html.twig', [
            'compte_partenaires' => $comptePartenaireRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="comptepartenairenew", methods={"GET","POST"})
     * @IsGranted("ROLE_PARTENAIRE")
     */
    public function ajout(Request $request,SerializerInterface $serializer, EntityManagerInterface $entityManager): Response
    {
        $comptePartenaire = new ComptePartenaire();
        $form=$this->createForm(ComptePartenaireType::class,$comptePartenaire);
        $data=json_decode($request->getContent(),true);
        //var_dump($comptePartenaire); die();
        $form->submit($data);
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($comptePartenaire);
            $entityManager->flush();
            return new Response('Compte Partenaire ajouter', Response::HTTP_CREATED);
    }

    /**
     * @Route("/{id}", name="compte_partenaire_show", methods={"GET"})
     * @IsGranted("ROLE_PARTENAIRE")
     */
    public function show(ComptePartenaire $comptePartenaire): Response
    {
        return $this->render('compte_partenaire/show.html.twig', [
            'compte_partenaire' => $comptePartenaire,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="compte_partenaire_edit", methods={"GET","POST"})
     * @IsGranted("ROLE_PARTENAIRE")

     */
    public function edit(Request $request, ComptePartenaire $comptePartenaire): Response
    {
        $form = $this->createForm(ComptePartenaireType::class, $comptePartenaire);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('compte_partenaire_index');
        }

        return $this->render('compte_partenaire/edit.html.twig', [
            'compte_partenaire' => $comptePartenaire,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="compte_partenaire_delete", methods={"DELETE"})
     * @IsGranted("ROLE_PARTENAIRE")

     */
    public function delete(Request $request, ComptePartenaire $comptePartenaire): Response
    {
        if ($this->isCsrfTokenValid('delete'.$comptePartenaire->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($comptePartenaire);
            $entityManager->flush();
        }

        return $this->redirectToRoute('compte_partenaire_index');
    }
}

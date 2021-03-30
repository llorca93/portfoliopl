<?php

namespace App\Controller;

use App\Entity\Apropos;
use App\Form\AproposType;
use App\Repository\AproposRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AproposController extends AbstractController
{
    /**
     * @Route("/admin/apropos", name="admin_apropos")
     */
    public function index(AproposRepository $aproposRepository): Response
    {
        $apropos = $aproposRepository->findAll();
        

        return $this->render('admin/apropos.html.twig', [
            'apropos' => $apropos,
        ]);
    }

    /**
     * @Route("/admin/apropos/create", name="admin_apropos_create")
     */
    public function createApropos(AproposRepository $aproposRepository, Request $request)
    {
        $apropos = new Apropos();
        $form = $this->createForm(AproposType::class, $apropos);
        $form->handleRequest($request);

        if ($form->isSubmitted && $form->isValid) {
            $manager = $this->getDoctrine()->getManager();
            $manager->persist($apropos);
            $manager->flush();
            $this->addFlash('success','Le texte a bien été ajouté');
        } else {
            $this->addFlash('danger', 'Une erreur est survenue lors de l\'ajout de la compétence');
        }

        return $this->render('admin/aproposForm.html.twig', [
            'aproposForm' => $form->createView(),
        ]);
        
    }

    /**
     * @Route("/admin/apropos/update-{id}", name="admin_apropos_update")
     */
    public function updateApropos(AproposRepository $aproposRepository, $id, Request $request)
    {
        $apropos = $aproposRepository->find($id);
        $form = $this->createForm(AproposType::class, $apropos);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $manager = $this->getDoctrine()->getManager();
            $manager->persist($apropos);
            $manager->flush();
            $this->addFlash('success','Le texte a bien été modifié');
            return $this->redirectToRoute('admin_apropos');
        }

        return $this->render('admin/aproposForm.html.twig', [
            'aproposForm' => $form->createView(),
        ]);
    }

    /**
     * @Route("/admin/apropos/delete-{id}", name="admin_apropos_delete")
     */
    public function deleteApropos(AproposRepository $aproposRepository, $id)
    {
        $apropos = $aproposRepository->find($id);

        $manager = $this->getDoctrine()->getManager();
        $manager->remove($apropos);
        $manager->flush();
        $this->addFlash('success', 'Le texte de présentation a bien été supprimé');

        return $this->redirectToRoute('admin_apropos');
    }
}

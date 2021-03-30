<?php

namespace App\Controller;

use App\Entity\Competences;
use App\Form\CompetencesType;
use App\Repository\CompetencesRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class CompetencesController extends AbstractController
{
    /**
     * @Route("/admin/competences", name="admin_competences")
     */
    public function index(CompetencesRepository $competencesRepository): Response
    {
        $competences = $competencesRepository->findAll();

        return $this->render('admin/competences.html.twig', [
            'competences' => $competences,
        ]);
    }

    /**
     * @Route("/admin/competences/create", name="admin_competences_create")
     */
    public function createCompetence(Request $request)
    {
        $competence = new Competences();
        $form = $this->createForm(CompetencesType::class, $competence);
        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            if ($form->isValid()) {
                $infoImg = $form['image']->getData(); 
                $extensionImg = $infoImg->guessExtension(); 
                $nomImg = time() . '-1.' . $extensionImg; 
                $infoImg->move($this->getParameter('dossier_photos_competences'), $nomImg); 
                $competence->setImage($nomImg); 

                $manager = $this->getDoctrine()->getManager();
                $manager->persist($competence);
                $manager->flush();
                $this->addFlash('success','La compétence a bien été ajouté');
            } else {
                $this->addFlash('danger', 'Une erreur est survenue lors de l\'ajout de la compétence');
            }
        }

        return $this->render('admin/competencesForm.html.twig', [
            'competencesForm' => $form->createView(),
        ]);
    }

    /**
     * @Route("/admin/competences/update-{id}", name="admin_competences_update")
     */
    public function updateCompetence(CompetencesRepository $competencesRepository, $id, Request $request)
    {
        $competence = $competencesRepository->find($id);
        $form = $this->createForm(CompetencesType::class, $competence);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $infoImg = $form['image']->getData();
            $nomOldImg = $competence->getImage();
            if ($infoImg !== null) {
                
                $cheminImg = $this->getParameter('dossier_photos_competences') . '/' . $nomOldImg;
                
                if (file_exists($cheminImg)) {
                    unlink($cheminImg);
                }

                $extensionImg = $infoImg->guessExtension(); // recupere l'extension de l'image
                $nomImg = time() . '-1.' . $extensionImg; // cree un nom unique pour l'image
                $infoImg->move($this->getParameter('dossier_photos_competences'), $nomImg); // deplacer l'image dans le dossier adequat
                $competence->setImage($nomImg); // definit le nom de l'image a mettre en bdd


            } else {
                $competence->setImage($nomOldImg);
            }

            $manager = $this->getDoctrine()->getManager();
            $manager->persist($competence);
            $manager->flush();
            $this->addFlash('success','La compétence a bien été modifié');
            return $this->redirectToRoute('admin_competences');
        }

        return $this->render('admin/competencesForm.html.twig', [
            'competencesForm' => $form->createView(),
        ]);
    }

    /**
     * @Route("/admin/competences/delete-{id}", name="admin_competences_delete")
     */
    public function deleteCompetence(CompetencesRepository $competencesRepository, $id)
    {
        $competence = $competencesRepository->find($id);

        $nomImg = $competence->getImage();
        if ($nomImg !== null) {
            $cheminImg = $this->getParameter('dossier_photos_competences') . '/' . $nomImg;
            if (file_exists($cheminImg)) {
                unlink($cheminImg);
            }
        }

        $manager = $this->getDoctrine()->getManager();
        $manager->remove($competence);
        $manager->flush();
        $this->addFlash('success', 'La compétence a bien été supprimé');

        return $this->redirectToRoute('admin_competences');
    }
}

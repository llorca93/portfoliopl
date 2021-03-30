<?php

namespace App\Controller;

use App\Form\ContactType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ContactController extends AbstractController
{
    /**
     * @Route("/contact", name="contact")
     */
    public function index(Request $request, \Swift_Mailer $mailer): Response
    {

        $form = $this->createForm(ContactType::class);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) { // si le formulaire a été soumis et est valide
            $contact = $form->getData(); // récupère les infos du formulaire
            if ($contact['fichier'] !== null) {
                $extensionFichier = $contact['fichier']->guessExtension(); // récupere l'extension de fichier
                $pieceJointe = (\Swift_Attachment::fromPath($contact['fichier']->getPathName())) // prépare la piece jointe (à partir d'un chemin)
                    ->setFileName('fichier' . $extensionFichier) // définit le nom du fichier
            ;
        
        }    
        $mail = (new \Swift_Message('PIERRE LLORCA - demande de contact - ' . $contact['objet'])) // prépare le mail (avec son titre)
            
            ->setFrom([$contact['email'] => $contact['email']]) // définit l'expéditeur
            ->setTo('p.llorca.pl@gmail.com') // définit le destinataire
            ->setBody( // définit le corps du message
                $this->renderView('contact/emailContact.html.twig', [ // passe les informations du formulaire au template de mail
                    'nom' => $contact['nom'],
                    'prenom' => $contact['prenom'],
                    'email' => $contact['email'],
                    'objet' => $contact['objet'],
                    'message' => $contact['message']
                ]),
                'text/html'  // définit le format du message
            )
            
        ;
        if ($contact['fichier'] !== null) {
            $mail->attach($pieceJointe); // attacher la piece jointe
        }
        $mailer->send($mail); // envoit le mail
        $this->addFlash('success', 'Votre message a bien été envoyé'); // message de succes
        return $this->redirectToRoute('contact');
        }

        return $this->render('contact/contact.html.twig', [
            'contactForm' => $form->createView(),
        ]);
    }
}

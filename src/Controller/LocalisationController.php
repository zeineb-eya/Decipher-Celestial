<?php

namespace App\Controller;

use App\Entity\Localisation;
use App\Entity\Planinng;
use App\Form\LocalisationType;
use App\Form\PlaninngType;
use App\Form\mailType;

use App\Repository\LocalisationRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Flex\Unpack\Result;


/**
 * @Route("/localisation")
 */
class LocalisationController extends AbstractController
{
    /**
     * @Route("/", name="localisation_index", methods={"GET"})
     */
    public function index(LocalisationRepository $localisationRepository): Response
    {
        return $this->render('localisation/index.html.twig', [
            'localisations' => $localisationRepository->findAll(),
        ]);
    }


    /**
     * @Route("/local", name="localisation_front", methods={"GET"})
     */
    public function indexfront(LocalisationRepository $localisationRepository): Response
    {
        return $this->render('localisation/showfront.html.twig', [
            'localisations' => $localisationRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="localisation_new", methods={"GET", "POST"})
     */
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $localisation = new Localisation();
        $form = $this->createForm(LocalisationType::class, $localisation);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($localisation);
            $entityManager->flush();

            return $this->redirectToRoute('localisation_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('localisation/new.html.twig', [
            'localisation' => $localisation,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/show/{id}", name="localisation_show", methods={"GET"})
     */
    public function show(Localisation $localisation): Response
    {
        return $this->render('localisation/show.html.twig', [
            'localisation' => $localisation,
        ]);
    }


/**
     * @Route("/mail", name="mail")
     */
    public function mail(Request $request, \Swift_Mailer $mailer)
    {
        $form = $this->createForm(mailType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $mail = $form->getData();
           // Ici nous enverrons l'e-mail
            //dd($contact);
            $message = (new \Swift_Message('Celestial Voyage') )
            //On attribue l'expediteur
            ->setFrom($mail['email'])
            // destinataire

            ->setTo('skanderrachah77@gmail.com')
            
            // le contenu de notre msg avec Twig
            ->setBody(
                $this->renderView(
                    'emails/email.html.twig', compact('mail')
                ),
                'text/html'
            )
            ;
            //on envoie le msg
            $mailer->send($message);
            $this->addFlash('success', 'Votre email a été bien envoyé');
            return $this->redirectToRoute('localisation_index');

        }
        return $this->render('localisation/email.html.twig',[
            'emailForm' => $form->createView()
        ]);
    }



    /**
     * @Route("/{id}", name="localisation_showfront", methods={"GET"})
     */
    public function showfront(Localisation $localisation): Response
    {
        return $this->render('localisation/showfront.html.twig', [
            'localisation' => $localisation,
        ]);
    }



    
    /**
     * @Route("/{id}/edit", name="localisation_edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, Localisation $localisation, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(LocalisationType::class, $localisation);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('localisation_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('localisation/edit.html.twig', [
            'localisation' => $localisation,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="localisation_delete", methods={"POST"})
     */
    public function delete(Request $request, Localisation $localisation, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$localisation->getId(), $request->request->get('_token'))) {
            $entityManager->remove($localisation);
            $entityManager->flush();
        }

        return $this->redirectToRoute('localisation_index', [], Response::HTTP_SEE_OTHER);
    }








}

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
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;

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


    /////////////////////////////////////JSON//////////
//Affichage ///



/**
     * @Route("/AllLocalisations", name="AllLocalisations")
     */
    public function JSONindex(LocalisationRepository $LocalisationRepository, SerializerInterface $serializer): Response
    {
        $result = $LocalisationRepository->findAll();
        $json = $serializer->serialize($result, 'json', ['groups' => 'Localisation:read']);
        return new JsonResponse($json, 200, [], true);
    }


       ///////////AjoutJson////////////////
    
/**
     * @Route("/ajoutLocalisationjson", name="ajoutLocalisationjson")
     */
    public function ajoutLocalisationjson(Request $request, SerializerInterface $serilazer, EntityManagerInterface $em)
    {
        $em = $this->getDoctrine()->getManager();
        $localisation = new Localisation();
        $heureDepart_localisation  = new \DateTime("now"); 
        $heureArrivee_loacalisation  = new \DateTime("now"); 
        $localisation->setPositionDepartLocalisation($request->get('positionDepart_localisation'));
        $localisation->setPositionAriveePlanning($request->get('positionArivee_planning'));
        $localisation->setFusee($request->get('fusee'));
        $localisation->setHeureDepartLocalisation($heureDepart_localisation);
        $localisation->setHeureArriveeLoacalisation($heureArrivee_loacalisation);
        $em->persist($localisation);
        $em->flush();

        $jsonContent = $serilazer->serialize($localisation, 'json', ['groups' => "Localisation:read"]);
        return new Response(json_encode($jsonContent));
    }


/////////////delete JSON///////////////
/**
     * @Route("/deleteLocalisationjson", name="delete_localisationjson")
     * @Method("DELETE")
     */

    public function deleteLocalisationjson(Request $request)
    {
        $id = $request->get("id");

        $em = $this->getDoctrine()->getManager();
        $localisation = $em->getRepository(Localisation::class)->find($id);
        if ($localisation != null) {
            $em->remove($localisation);
            $em->flush();

            $serialize = new Serializer([new ObjectNormalizer()]);
            $formatted = $serialize->normalize("Localisation supprime avec succes");
            return new JsonResponse($formatted);
        }
        return new JsonResponse("id de localisation est invalide");
    }



    ///////////////////////update///////

     /**
     * @Route("/modifLocalisationjson/{id}", name="modifLocalisationjson")
     */
    public function modifLocalisationjson(Request $request, SerializerInterface $serilazer, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $localisation = $em->getRepository(Localisation::class)->find($id);
        //  $user = $em->getRepository(User::class)->find($user_id);
        $heureDepart_localisation  = new \DateTime("now"); 
        $heureArrivee_loacalisation  = new \DateTime("now"); 
        $localisation->setPositionDepartLocalisation($request->get('positionDepart_localisation'));
        $localisation->setPositionAriveePlanning($request->get('positionArivee_planning'));
        $localisation->setFusee($request->get('fusee'));
        $localisation->setHeureDepartLocalisation($heureDepart_localisation);
        $localisation->setHeureArriveeLoacalisation($heureArrivee_loacalisation);

        $em->persist($localisation);
        $em->flush();
        $jsonContent = $serilazer->serialize($localisation, 'json', ['groups' => "Localisation:read"]);
        return new Response(json_encode($jsonContent));;
    }
////////////////////////detail///////
/**
     * @Route("/detailLocalisationjson/{id}", name="detailLocalisationjson")
     */
    public function detailLocalisationjson(Request $request, SerializerInterface $serilazer, $id): Response
    {
        $user = $request->get("id");

        $em = $this->getDoctrine()->getManager();
        $localisation = $em->getRepository(Localisation::class)->find($id);
        $json = $serilazer->serialize($localisation, 'json', ['groups' => "Localisation:read"]);
        return new JsonResponse($json, 200, [], true);
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

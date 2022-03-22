<?php

namespace App\Controller;

use App\Entity\Billet;
use App\Entity\Reservation;
use App\Entity\Localisation;
use App\Entity\User;
use App\Form\ReservationType;
use App\Repository\ReservationRepository;
use App\Form\BilletType;
use App\Repository\BilletRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;

use Symfony\Component\Validator\Validator\ValidatorInterface;

// Include paginator interface
use Knp\Component\Pager\PaginatorInterface;

/**
 * @Route("/billet")
 */
class BilletController extends AbstractController
{
    /**
     * @Route("/", name="billet_index", methods={"GET"})
     */
    public function index(Request $request, BilletRepository $billetRepository, PaginatorInterface $paginator): Response
    {// Retrieve the entity manager of Doctrine
        $em = $this->getDoctrine()->getManager();
        // Get some repository of data, in our case we have an Billet entity
        $billetRepository = $em->getRepository(Billet::class);
        // Find all the data on the billets table, filter your query as you need
        $allbilletQuery = $billetRepository->createQueryBuilder('p')
            ->where('p.localisation != :localisation')
            ->setParameter('localisation', 'canceled')
            ->getQuery();
             // Paginate the results of the query
          $billets = $paginator->paginate(
              // Doctrine Query, not results
              $allbilletQuery,
              // Define the page parameter
              $request->query->getInt('page', 1),
              // Items per page
              5
          );
        
        return $this->render('billet/index.html.twig', [
          //  'billets' => $billetRepository->findAll(),
            'billets' => $billets,
        ]);
       
    }
    /*****************************************************************************************************/
    /**
     * @Route("/AllBillets/json", name="AllBillets")
     */
    public function AllBillets(BilletRepository $rep,SerializerInterface $serilazer):Response
    {
        $billets= $rep->findAll();

        $json= $serilazer->serialize($billets,'json',['groups'=>"billet:read"]);
        return new JsonResponse($json,200,[],true);
    }
    /**
     * @Route("/AddBillets/json/{id}", name="AddBillets")
     */
    public function AddBilletsJSON(Request $request,SerializerInterface $serilazer,Localisation $id)
    {
        $em = $this->getDoctrine()->getManager();
        $billet = new Billet();
        $embarquement = new \DateTime("Tomorrow");
       // $localisation = $this->getLocalisation();
        $localisation = $em->getRepository(Localisation::class)->find($id);
        $billet->setChairBillet($request->get('chair_billet'));
        $billet->setVoyageNum($request->get('voyage_num'));
        $billet->setTerminal($request->get('terminal'));
        $billet->setPortail($request->get('portrail'));
        $billet->setEmbarquement($embarquement);
        $billet->setLocalisation($localisation);

        $em->persist($billet);
        $em->flush();

        $jsonContent= $serilazer->serialize($billet,'json',['groups'=>"billet:read"]);
        return new Response(json_encode($jsonContent));
        //$serializer = new Serializer([new ObjectNormalizer()]);
       // $formatted = $serializer->normalize($billet);
      //  return new JsonResponse($formatted);
    }

    /**
     * @Route("/UpdateBillets/json/{id}/{localisation_id}", name="UpdateBillets")
     */
    public function UpdateBilletsJSON(Request $request,SerializerInterface $serilazer,$id,Localisation $localisation_id)
    {
        $em = $this->getDoctrine()->getManager();
        $embarquement = new \DateTime("Tomorrow");
        
        $billet = $em->getRepository(Billet::class)->find($id);
        $localisation = $em->getRepository(Localisation::class)->find($localisation_id);
        
        $billet->setChairBillet($request->get('chair_billet'));
        $billet->setVoyageNum($request->get('voyage_num'));
        $billet->setTerminal($request->get('terminal'));
        $billet->setPortail($request->get('portrail'));
        $billet->setEmbarquement($embarquement);
        $billet->setLocalisation($localisation);
        $em->flush();
        $jsonContent= $serilazer->serialize($billet,'json',['groups'=>"billet:read"]);
        return new Response(json_encode($jsonContent));;
    }
    /**
     * @Route("/DetailBillets/json/{id}", name="DetailBillets")
     */
    public function DetailBilletsJSON(Request $request,SerializerInterface $serilazer,$id):Response
    {
        $em = $this->getDoctrine()->getManager();
        $billets= $em->getRepository(Billet::class)->find($id);
      $json= $serilazer->serialize($billets,'json',['groups'=>"billet:read"]);
        return new JsonResponse($json,200,[],true);
    }
    /**
     * @Route("/DeleteBillets/json/{id}", name="DeleteBillets")
     */
    public function DeleteBilletsJSON(Request $request,SerializerInterface $serilazer,$id)
    {
        $em = $this->getDoctrine()->getManager();
        $billet = $em->getRepository(Billet::class)->find($id);
        $em->remove($billet);
        $em->flush();
        $jsonContent= $serilazer->serialize($billet,'json',['groups'=>"billet:read"]);
        return new Response(json_encode($jsonContent));;
    }
    /*****************************************************************************************************/
    /**
     * @Route("/billets", name="billet_front", methods={"GET"})
     */
    public function indexfront(Request $request, BilletRepository $billetRepository, PaginatorInterface $paginator): Response
    {// Retrieve the entity manager of Doctrine
        $em = $this->getDoctrine()->getManager();
        // Get some repository of data, in our case we have an Billet entity
        $billetRepository = $em->getRepository(Billet::class);
        // Find all the data on the billets table, filter your query as you need
        $allbilletQuery = $billetRepository->createQueryBuilder('p')
            ->where('p.localisation != :localisation')
            ->setParameter('localisation', 'canceled')
            ->getQuery();
             // Paginate the results of the query
          $billets = $paginator->paginate(
              // Doctrine Query, not results
              $allbilletQuery,
              // Define the page parameter
              $request->query->getInt('page', 1),
              // Items per page
              3
          );
        
        return $this->render('billet/indexfront.html.twig', [
          //  'billets' => $billetRepository->findAll(),
            'billets' => $billets,
        ]);
        
    }
    /**
     * @Route("/new", name="billet_new", methods={"GET", "POST"})
     */
    public function new(Request $request, EntityManagerInterface $entityManager,ValidatorInterface $validator): Response
    {
        $billet = new Billet();
        $form = $this->createForm(BilletType::class, $billet);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $errors = $validator->validate($billet);
            if (count($errors) > 0) {
                /*
                 * Uses a __toString method on the $errors variable which is a
                 * ConstraintViolationList object. This gives us a nice string
                 * for debugging.
                 */
                $errorsString = (string) $errors;
        
                return new Response($errorsString);
            }
            $entityManager->persist($billet);
            $entityManager->flush();
            $this->addFlash('success', 'Ticket Created!');
            return $this->redirectToRoute('billet_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('billet/new.html.twig', [
            'billet' => $billet,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="billet_show", methods={"GET"})
     */
    public function show(Billet $billet): Response
    {
        return $this->render('billet/show.html.twig', [
            'billet' => $billet,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="billet_edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, Billet $billet, EntityManagerInterface $entityManager,ValidatorInterface $validator): Response
    {
        $form = $this->createForm(BilletType::class, $billet);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $errors = $validator->validate($billet);
            if (count($errors) > 0) {
                /*
                 * Uses a __toString method on the $errors variable which is a
                 * ConstraintViolationList object. This gives us a nice string
                 * for debugging.
                 */
                $errorsString = (string) $errors;
        
                return new Response($errorsString);
            }
            $entityManager->flush();

            $this->addFlash('success', 'Ticket Edited!');

            return $this->redirectToRoute('billet_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('billet/edit.html.twig', [
            'billet' => $billet,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="billet_delete", methods={"POST"})
     */
    public function delete(Request $request, Billet $billet, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$billet->getId(), $request->request->get('_token'))) {
            $entityManager->remove($billet);
            $entityManager->flush();
        }

        $this->addFlash('success', 'Ticket Deleted!');

        return $this->redirectToRoute('billet_index', [], Response::HTTP_SEE_OTHER);
    }
   
    /**
     * @Route("/showBillet/{id}", name="showBillet")
     */
 /*   public function showBillet($id)
    {
        $billet = $this->getDoctrine()->getRepository(Billet::class)->find($id);
        $reservations= $this->getDoctrine()->getRepository(Reservation::class)->listReservationByBillet($billet->getId());
        return $this->render('billet/showB.html.twig', [
            "billet" => $billet,
            "reservations"=>$reservations]);
    }*/


}

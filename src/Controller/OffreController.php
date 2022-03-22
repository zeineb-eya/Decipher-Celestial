<?php

namespace App\Controller;

use App\Entity\Offre;
use App\Entity\Planinng;
use App\Entity\PropertySearch;
use App\Form\PropertySearchType;
use App\Form\OffreType;
use App\Repository\OffreRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Query\Expr\From;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request; // Nous avons besoin d'accéder à la requête pour obtenir le numéro de page
use Knp\Component\Pager\PaginatorInterface; // Nous appelons le bundle KNP Paginator

use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use App\Notifications\CreationOffreNotification;
/**
 * @Route("/offre")
 */
class OffreController extends AbstractController
{

    /**
     * @var CreationOffreNotification
     */
    private $notify_creation ;

    public function __construct(CreationOffreNotification $notify_creation) {

        $this->notify_creation = $notify_creation;
    }


   
    /******************Index Back*************** */
    /**
     * @Route("/", name="offre_index", methods={"GET"})
     */
    public function index(Request $request,OffreRepository $offreRepository, PaginatorInterface $paginator): Response
    {

        $em = $this->getDoctrine()->getManager();
        // Get some repository of data, in our case we have an Billet entity
        $offreRepository = $em->getRepository(Offre::class);
        // Find all the data on the billets table, filter your query as you need
       
        $allOffreQuery = $offreRepository->createQueryBuilder('o')
           // ->where('o.nom_offre = :nom_offre')
          //  ->setParameter('id', 'canceled')
            ->getQuery();
             // Paginate the results of the query
          $offres = $paginator->paginate(
              // Doctrine Query, not results
              $allOffreQuery,
              // Define the page parameter
              $request->query->getInt('page', 1),
              // Items per page
              3
          );
          
      return $this->render('offre/index.html.twig', [
            'offres' => $offres,
        ]);
    }
    
    /***************************IndexFront **************************/
    /**
     * @Route("/indexOffreTest", name="offre_indexOffreTest", methods={"GET"})
     */
    public function indexF(Request $request,OffreRepository $offreRepository, PaginatorInterface $paginator): Response
    {
     
        $em = $this->getDoctrine()->getManager();
        // Get some repository of data, in our case we have an Billet entity
        $offreRepository = $em->getRepository(Offre::class);
        // Find all the data on the billets table, filter your query as you need
       
        $allOffreQuery = $offreRepository->createQueryBuilder('o')
           // ->where('o.nom_offre = :nom_offre')
          //  ->setParameter('id', 'canceled')
            ->getQuery();
             // Paginate the results of the query
          $offres = $paginator->paginate(
              // Doctrine Query, not results
              $allOffreQuery,
              // Define the page parameter
              $request->query->getInt('page', 1),
              // Items per page
              3
          );
            return $this->render('offre/indexOffreTest.html.twig', [
            'offres' => $offres,
        ]);
    }

    //Tri par Reduction Front ASC

     /**
     * @Route("/listOffreByReductionA", name="listOffreByReductionA", methods={"GET"})
     */
    public function listOffreByReductionA(OffreRepository $repo)
    {

        $offresByReductionA = $repo->orderByReductionOffreA();

        //orderByDate();
        return $this->render('offre/listOffreByReductionA.html.twig', [
            "offresByReductionA" => $offresByReductionA,
        ]);
    }

    
      //Tri par Reduction Front DESC

     /**
     * @Route("/listOffreByReduction", name="listOffreByReduction", methods={"GET"})
     */
    public function listOffreByReduction(OffreRepository $repo)
    {

        $offresByReduction = $repo->orderByReductionOffre();

        //orderByDate();
        return $this->render('offre/listByReductionOffre.html.twig', [
            "offresByReduction" => $offresByReduction,
        ]);
    }

 //Tri par Reduction Back DESC

     /**
     * @Route("/listOffreByReductionB", name="listOffreByReductionB", methods={"GET"})
     */
    public function listOffreByReductionB(OffreRepository $repo)
    {

        $offresByReduction = $repo->orderByReductionOffre();

        //orderByDate();
        return $this->render('offre/listOffreByReductionB.html.twig', [
            "offresByReduction" => $offresByReduction,
        ]);
    }


    /**
     * @Route("/new", name="offre_new", methods={"GET", "POST"})
     */
    public function new(Request $request, EntityManagerInterface $entityManager, ValidatorInterface $validator): Response
    {
        $offre = new Offre();
        
        $form = $this->createForm(OffreType::class, $offre);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $errors = $validator->validate($offre);
            if (count($errors) > 0) {
                /*
         * Uses a __toString method on the $errors variable which is a
         * ConstraintViolationList object. This gives us a nice string
         * for debugging.
         */ 
     /*   $item->setPrice($product->getPrice());
        $item->setDiscount(20);*/

                $errorsString = (string) $errors;
                /*$planning->getPrixPlanning();
                $offre->setPrixOffre();*/
            /*    $offre->setPrixOffre($planinng->getPrixPlanning() * $offre->getReduction());*/
              //  $getPrixOffre()->setPrix($planning->getPrix() * $reduction );

                return new Response($errorsString);
                return $this->render('offre/_form.html.twig', [
                    'errors' => $errors,
                ]);
            }
            
            $entityManager->persist($offre);
            
            $entityManager->flush();
            //envoyer mail ajout offre au client
            $this->notify_creation->notify();

            return $this->redirectToRoute('offre_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('offre/new.html.twig', [
            'offre' => $offre,
            'form' => $form->createView(),
        ]);
    }

    /****************Show Front******************** */
    /**
     * @Route("/offreFront/{id}", name="offre_showFront", methods={"GET"})
     */
    public function showF(Offre $offre): Response
    {
        return $this->render('offre/showFront.html.twig', [
            'offre' => $offre,
        ]);
    }

    /**
     * @Route("/{id}", name="offre_show", methods={"GET"})
     */
    public function show(Offre $offre)
    {

        return $this->render('offre/show.html.twig', [
            'offre' => $offre,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="offre_edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, Offre $offre, EntityManagerInterface $entityManager, ValidatorInterface $validator): Response
    {
        $form = $this->createForm(OffreType::class, $offre);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $errors = $validator->validate($offre);

            if (count($errors) > 0) {
                /*
         * Uses a __toString method on the $errors variable which is a
         * ConstraintViolationList object. This gives us a nice string
         * for debugging.
         */
                $errorsString = (string) $errors;

                return new Response($errorsString);
                return $this->render('offre/_form.html.twig', [
                    'errors' => $errors,
                ]);
            }

            $entityManager->flush();

            return $this->redirectToRoute('offre_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('offre/edit.html.twig', [
            'offre' => $offre,
            'form' => $form->createView(),
        ]);
    }

    //offre delete test
    //DELETE 

    /**
     * @Route("/offre/delete/{id}",name="delete_offre")
     * @Method({"DELETE"})
     */
    /*public function deleteo(Request $request, $id)
    {
        $offre = $this->getDoctrine()->getRepository(Offre::class)->find($id);
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->remove($offre);
        $entityManager->flush();
        $response = new Response();
        $response->send();
        $this->addFlash('success', 'Article Deleted!');
        return $this->redirectToRoute('offre_index');
    }
*/
    /**
     * @Route("/{id}", name="offre_delete", methods={"POST"})
     */
    public function delete(Request $request, Offre $offre, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete' . $offre->getId(), $request->request->get('_token'))) {
            $entityManager->remove($offre);
            $entityManager->flush();
        }

        return $this->redirectToRoute('offre_index', [], Response::HTTP_SEE_OTHER);
    }
    /**********************************Search lezm tetsala7 */
    /******************hetha li yet7at f index */
    /**<form method="post" action="{{ path(<'search') }}">
		<label>Rechercher</label>
		<input type="text" name="search">
		<input type="submit" value="Rechercher" class="btn btn-success">
	</form>
     */ //
    /**
     * @Route("/search", name="search")
     */

    /* function search(OffreRepository $repository, Request $request)
    {
        $data = $request->get('search');
        $offre = $repository->findBy(['nom_offre' => $data]);

        return $this->render('offre/show.html.twig', [
            'offre' => $offre,
        ]);
    }*/

      /**
       * @param OffreRepository $repository
       * @return Response
     * @Route("/ListDQL", name="search")
     */

    function OrderByNameDQL(OffreRepository $repository) {
$offre=$repository->OrderByName();
return $this->render('offre/index.html.twig', [
    'offre' => $offre
]); 
    }
}

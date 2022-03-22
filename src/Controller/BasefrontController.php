<?php

namespace App\Controller;

namespace App\Controller;


use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\CategoriePost;
use App\Entity\Post;
use Knp\Component\Pager\PaginatorInterface;
use App\Repository\PostRepository;

class BasefrontController extends AbstractController
{
   /**
     * @Route("/basefront", name="basefront")
     */
    public function index(): Response
    {
        $em=$this->getDoctrine();
        $categorypost=$em->getRepository(CategoriePost::class)->findAll();
        return $this->render('basefront/base2.html.twig', [
            'controller_name' => 'BasefrontController',
            'categoryPost' => $categorypost,
            
        ]);
    }
        /**
     * @Route("/", name="home")
     */
    public function home(): Response
    {
        $em=$this->getDoctrine();
        $categorypost=$em->getRepository(CategoriePost::class)->findAll();
        return $this->render('basefront/home.html.twig', [
            'controller_name' => 'BasefrontController',
            'categoryPost' => $categorypost,

        ]);
    }
        /**
     * @Route("/account", name="account")
     */
    public function account(): Response
    {
        return $this->render('basefront/account.html.twig', [
            'controller_name' => 'BasefrontController',
        ]);
    }
          /**
     * @Route("/services", name="services")
     */
    public function services(): Response
    {
        return $this->render('basefront/services.html.twig', [
            'controller_name' => 'BasefrontController',
        ]);
    }
          /**
     * @Route("/trips", name="trips")
     */
    public function trips(): Response
    {
        return $this->render('basefront/trips.html.twig', [
            'controller_name' => 'BasefrontController',
        ]);
    }

          /**
     * @Route("/reservations", name="reservations")
     */
    public function reservation(): Response
    {
        return $this->render('basefront/reservation.html.twig', [
            'controller_name' => 'BasefrontController',
        ]);
    }

          /**
     * @Route("/equipement", name="equipement")
     */
    public function equipement(): Response
    {
        return $this->render('basefront/equipement.html.twig', [
            'controller_name' => 'BasefrontController',
        ]);
    }

            /**
     * @Route("/blog", name="blog" )
     */
    public function blog($id): Response
    {
        $em=$this->getDoctrine();
        $categorypost=$em->getRepository(CategoriePost::class)->findAll();
          
        return $this->render('basefront/blog.html.twig', [
            'controller_name' => 'BasefrontController',
            'categoryPost' => $categorypost,
        ]);
    }
          /**
     * @Route("/tblog/{id}", name="tblog" )
     */
    public function tblog($id,Request $request,PostRepository $postRepository, PaginatorInterface $paginator): Response
    {
        $em=$this->getDoctrine();
        $categorypost=$em->getRepository(CategoriePost::class)->findAll();
        $idcategory = (int) $id ;
        $postes=$em->getRepository(Post::class)->getallbycategory($idcategory);    
        $donnes=$postRepository->findAll();
        $postes=$paginator->paginate(
            $donnes,
            $request->query->getInt('page',1),
            4
        );
        return $this->render('basefront/blog.html.twig', [
            'controller_name' => 'BasefrontController',
            'categoryPost' => $categorypost,
            'postes'=> $postes,
        ]);
       
    }
}

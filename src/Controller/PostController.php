<?php

namespace App\Controller;
use App\Entity\Post;
use App\Entity\Commentaire;
use App\Form\PostType;
use App\Repository\PostRepository;
use App\Repository\CommentaireRepository;
use Doctrine\ORM\EntityManagerInterface;
use Gedmo\Sluggable\Util\Urlizer;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Mediumart\Orange\SMS\SMS;
use Mediumart\Orange\SMS\Http\SMSClient;
use Dompdf\Dompdf;
use Dompdf\Options;
//use Symfony\Component\Mailer\MailerInterface;
//use Symfony\Component\Mime\Email;

/**
 * @Route("/post")
 */
class PostController extends AbstractController
{
   
    /**
     * @Route("/", name="post_index", methods={"GET"})
     */
    public function index(Request $request,PostRepository $postRepository, PaginatorInterface $paginator): Response
    {
        $donnes=$postRepository->findAll();
        $posts=$paginator->paginate(
            $donnes,
            $request->query->getInt('page',1),
            4
        );

        return $this->render('post/index.html.twig', [
            'posts' => $posts,
        ]);
    }
    
    /**
     * @Route("/Allposts/json", name="AllPosts")
     */
    public function AllPosts(PostRepository $rep,SerializerInterface $serilazer):Response
    {
        $posts= $rep->findAll();

        $json= $serilazer->serialize($posts,'json',['groups'=>"post:read"]);
        return new JsonResponse($json,200,[],true);
    }
/**
     * @Route("/AddPosts/json", name="AddPosts")
     */
    public function AddPostsJSON(Request $request,NormalizerInterface $Normalizer)
    {
        $em = $this->getDoctrine()->getManager();
        $Post = new Post();
        $Post->setNom($request->get('nom'));
        $Post->setImgPost($request->get('img_post'));
        $Post->setDescriptionPost($request->get('description_post'));
        $em->persist($Post);
        $em->flush();

        $jsonContent= $Normalizer->normalize($Post,'json',['groups'=>"post:read"]);
        return new Response(json_encode($jsonContent));;
    }

    /**
     * @Route("/new", name="post_new", methods={"GET", "POST"})
     */
    public function new(Request $request, EntityManagerInterface $entityManager,ValidatorInterface $validator,\Swift_Mailer $mailer): Response
    {
        $post = new Post();
        $form = $this->createForm(PostType::class, $post);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $uploadedFile = $form['img_post']->getData();
            $pathupload = $this->getParameter('kernel.project_dir').'/public/uploads/images';
            $originalFilename = pathinfo($uploadedFile->getClientOriginalName(), PATHINFO_FILENAME);
            $newFilename = Urlizer::urlize($originalFilename).'-'.uniqid().'.'.$uploadedFile->guessExtension();
            $uploadedFile->move(
                $pathupload,
                $newFilename
            );
            $post->setImgPost($newFilename);

            
         
            $entityManager=$this->getDoctrine()->getManager(); 
            $entityManager->persist($post);
            $entityManager->flush();
            
            //$contact = $form->getData();
            $message = (new \Swift_Message('you got mail  +++')) 
                        ->setFrom('celestialservice489@gmail.com')
                        ->setTo('yacoubi.fatima@esprit.tn')
                        ->setBody('new article','text/html') ;
            $mailer->send($message);
         // $this->addFlash('message','message a bien ete envoyee');
            return $this->redirectToRoute('post_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('post/new.html.twig', [
            'post' => $post,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/show/{id}", name="post_show", methods={"GET"})
     */
    public function show(Post $post): Response
    {
        return $this->render('post/show.html.twig', [
            'post' => $post,
        ]);
    }
     /**
     * @Route("/imprimerPost", name="post_imprimer", methods={"GET"})
     */
    public function imprimerPost(Request $request,PostRepository $postRepository): Response
    {
// Configure Dompdf according to your needs
$pdfOptions = new Options();
$pdfOptions->set('defaultFont', 'Arial');

// Instantiate Dompdf with our options
$dompdf = new Dompdf($pdfOptions);


//$Post = new Post();
//$id = $_GET['id'];	
//$posts = $this->getDoctrine()->getRepository(Post::class)->find($id);


$posts=$postRepository->findAll();

// Retrieve the HTML generated in our twig file
$html = $this->renderView('basefront/ImprimePost.html.twig', [
    'postes' => $posts,
]);

// Load HTML to Dompdf
$dompdf->loadHtml($html);

// (Optional) Setup the paper size and orientation 'portrait' or 'portrait'
$dompdf->setPaper('A3', 'portrait');

// Render the HTML as PDF
$dompdf->render();

// Output the generated PDF to Browser (force download)
$dompdf->stream("PostDetail.pdf", [
    "Attachment" => false
]);


    }
/**
     * @Route("/postdetails/{id}", name="get_post_show", methods={"GET"})
     */
    public function get_post_show(Request $request): Response
    {
        $commentairs= new Commentaire();
        $Post = new Post();
        $id = intval($_GET['id']);	
        $Post = $this->getDoctrine()->getRepository(Post::class)->find($id);
        $commentairs=$this->getDoctrine()->getRepository(Commentaire::class)->findBy(array('post' => $id));
        
        return $this->render('basefront/DetailsPost.html.twig', array(
            'post'=>$Post ,'commentairs'=>$commentairs
        ));
    }

    /**
     * @Route("/newCommentaire", name="/post/commentaire_newPost")
     */
    public function newComment(Request $request, EntityManagerInterface $entityManager,ValidatorInterface $validator): Response
    {
        $commentaire = new Commentaire();
        dump('aa');
       
    }


    /**
     * @Route("/bycategory/{id}", name="get_allpost_show", methods={"GET"})
     */
    public function getAllPostbyCategory(): Response
    {
        $em=$this->getDoctrine();
        $categorypost=$em->getRepository(Post::class)->findBy(array('categoriePost' => id));
        return $this->render('basefront/blog.html.twig', [
            'post' => $post,
        ]);
    }
    /**
     * @Route("/{id}/edit", name="post_edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, Post $post, EntityManagerInterface $entityManager,ValidatorInterface $validator): Response
    {
        $form = $this->createForm(PostType::class, $post);
        $form->handleRequest($request);
         
      if ($form->isSubmitted() && $form->isValid()) {
            if ($form['img_post']){
                $uploadedFile = $form['img_post']->getData();
                $pathupload = $this->getParameter('kernel.project_dir').'/public/uploads/img_post';
                $originalFilename = pathinfo($uploadedFile->getClientOriginalName(), PATHINFO_FILENAME);
                $newFilename = Urlizer::urlize($originalFilename).'-'.uniqid().'.'.$uploadedFile->guessExtension();
                $uploadedFile->move(
                    $pathupload,
                    $newFilename
                );
                $post->setImgPost($newFilename);
            }
            $entityManager->flush();

            return $this->redirectToRoute('post_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('post/edit.html.twig', [
            'post' => $post,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/admin/delete/{id}", name="post_delete", methods={"POST"})
     */
    public function delete(Request $request, Post $post, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$post->getId(), $request->request->get('_token'))) {
            $entityManager->remove($post);
            $entityManager->flush();
        }

        return $this->redirectToRoute('post_index', [], Response::HTTP_SEE_OTHER);
    }

    /**
     * @Route("/searchPostajax ", name="ajaxPost")
     */
    public function searchPosteajax(Request $request)
        {
            $repository = $this->getDoctrine()->getRepository(Post::class);
            $requestString=$request->get('searchValue');
            $posts = $repository->findPostbyname($requestString);

            return $this->render('basefront/postajax.html.twig', [
                "postes"=>$posts
            ]);
        }
}




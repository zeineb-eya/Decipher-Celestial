<?php

namespace App\Controller;

use App\Entity\Commentaire;
use App\Form\CommentaireType;
use App\Entity\Post;
use App\Repository\CommentaireRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Knp\Component\Pager\PaginatorInterface;

/**
 * @Route("/commentaire")
 */
class CommentaireController extends AbstractController
{
    /**
     * @Route("/", name="commentaire_index", methods={"GET"})
     */
    public function index(Request $request,CommentaireRepository $commentaireRepository, PaginatorInterface $paginator): Response
    {
        $donnes=$commentaireRepository->findAll();
        $comments=$paginator->paginate(
            $donnes,
            $request->query->getInt('page',1),
            2
        );

        return $this->render('commentaire/index.html.twig', [
            'commentaires' => $comments,
        ]);
    }
    
    /**
     * @Route("/new", name="commentaire_new", methods={"GET", "POST"})
     */
    public function new(Request $request, EntityManagerInterface $entityManager,ValidatorInterface $validator): Response
    {
        $commentaire = new Commentaire();
        $form = $this->createForm(CommentaireType::class, $commentaire);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $errors = $validator->validate($commentaire);
            if (count($errors) > 0) {
                $errorsString = (string) $errors;
        
                return new Response($errorsString);
            }
            $entityManager->persist($commentaire);
            $entityManager->flush();

            return $this->redirectToRoute('commentaire_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('commentaire/new.html.twig', [
            'commentaire' => $commentaire,
            'form' => $form->createView(),
        ]);
    }
 /**
     * @Route("/newcommentaire", name="newCommentaire", methods={"GET", "POST"})
     */
    public function newCommentaire(Request $request, EntityManagerInterface $entityManager): Response
    {
       if ($request->request->get('inputComment')!= "") {

       /* $commentairs= new Commentaire();
        $Post = new Post();
        $id =(int) $request->request->get('poste');
        $Post = $this->getDoctrine()->getRepository(Post::class)->find($id);
        $commentaire = new Commentaire();
        $commentaire->setPost($Post);
        $commentaire->setMsgCommentaire($request->request->get('inputComment'));
        $entityManager->persist($commentaire);
        $entityManager->flush();
        $commentairs=$this->getDoctrine()->getRepository(Commentaire::class)->findBy(array('post' => $id));*/
        $commentairs= new Commentaire();
        $Post = new Post();
        $id =(int) $request->request->get('poste');
        $Post = $this->getDoctrine()->getRepository(Post::class)->find($id);
        $commentairs->setPost($Post);
        $commentairs->setMsgCommentaire($request->request->get('inputComment'));
        $entityManager->persist($commentairs);
        $entityManager->flush();
        $commentairs=$this->getDoctrine()->getRepository(Commentaire::class)->findBy(array('post' => $id));
    }
     return $this->render('basefront/DetailsPost.html.twig', array('post'=>$Post ,'commentairs'=>$commentairs));
    
     // return $this->redirectToRoute('get_post_show', [], Response::HTTP_SEE_OTHER);
    }

    /**
     * @Route("/{id}", name="commentaire_show", methods={"GET"})
     */
    public function show(Commentaire $commentaire): Response
    {
        return $this->render('commentaire/show.html.twig', [
            'commentaire' => $commentaire,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="commentaire_edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, Commentaire $commentaire, EntityManagerInterface $entityManager,ValidatorInterface $validator): Response
    {
        $form = $this->createForm(CommentaireType::class, $commentaire);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $errors = $validator->validate($commentaire);
            if (count($errors) > 0) {
                $errorsString = (string) $errors;
        
                return new Response($errorsString);
            }
            $entityManager->flush();

            return $this->redirectToRoute('commentaire_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('commentaire/edit.html.twig', [
            'commentaire' => $commentaire,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="commentaire_delete", methods={"POST"})
     */
    public function delete(Request $request, Commentaire $commentaire, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$commentaire->getId(), $request->request->get('_token'))) {
            $entityManager->remove($commentaire);
            $entityManager->flush();
        }

        return $this->redirectToRoute('commentaire_index', [], Response::HTTP_SEE_OTHER);
    }
    /**
     * @Route("/Allcomments/Json", name="Allcommentaire", methods={"GET"})
     */
    public function JSONindex(CommentaireRepository $Rep,SerializerInterface $serializer): Response
    {
        $result = $Rep->findAll();
        /* $n = $normalizer->normalize($result, null, ['groups' => 'livreur:read']);
        $json = json_encode($n); */
        $json = $serializer->serialize($result, 'json', ['groups' => 'commentaire:read']);
        return new JsonResponse($json, 200, [], true);
    }
}

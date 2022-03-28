<?php

namespace App\Controller;
use App\Entity\User;
use App\Form\UserType;
use App\Form\UserType1;
use App\Repository\UsersRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;


class UserController extends AbstractController
{   /**
    * @Route("/search", name="ajax_search")
    * @Method("GET")
    */
   public function searchAction(Request $request)
   {
       $em = $this->getDoctrine()->getManager();
 
       $requestString = $request->get('q');
 
       $User =  $em->getRepository(User::class)->findEntitiesByString($requestString);
 
       if(!$User) {
           $result['User']['error'] = "Not Found";
       } else {
           $result['User'] = $this->getRealEntities($User);
       }
 
       return new Response(json_encode($result));
   }
 
   public function getRealEntities($User){
 
       foreach ($User as $User){
           $realEntities[$User->getId()] = [$User->getMailUtilisateur(),$User->getPrenomUtilisateur(),$User->getPrenomUtilisateur()];
       }
 
       return $realEntities;
   }
    
    // /**
   // * @Route("/A", name="AllUsers", methods={"GET"})
    //*/
    /**
   public function AllUsers( NormalizerInterface $Normalizer)
   {
       $repository = $this->getDoctrine()->getRepository(User::class);
       $users = $repository->findAll();
       $jsonContent = $Normalizer->normalize($users,'json',['groups'=>'post:read']);
       return $this->render('user/allUserJSON.html.twig',['data'=>$jsonContent]);
   } */
/**
    * @Route("/B/{id}", name="AllUsers", methods={"GET"})
    */
  /** public function UserId(Request $request,$id,NormalizerInterface $Normalizer)
{$em =$this->getDoctrine()->getManager();
$user= $em->getRepository(User::class)->find($id);
$jsonContent = $Normalizer->normalize($user,'json',['groups'=>'post:read']);
return new Response(json_encode($jsonContent));
}
public function addUserJSON(Request $request,NormalizeInterface $Normalizer)
{
    $em =$this->getDoctrine()->getManager();
    $user = new User();
    $user->setMailUtilisateur($request->get('email'));
    $em->persist($user);
    $em->flush();
    $jsonContent = $Normalizer->normalize($user,'json',['groups'=>'post:read']);
return new Response(json_encode($jsonContent));
}   
 */

/**
     * @Route("/user/", name="user_index", methods={"GET"})
     */
    public function index(UsersRepository $userRepository): Response
    {
        return $this->render('user/index.html.twig', [
            'users' => $userRepository->findAll(),
        ]);
    }
    /**
     * @Route("/user/new", name="user_new", methods={"GET", "POST"})
     */
    public function new(Request $request, UserPasswordEncoderInterface $userPasswordEncoder, EntityManagerInterface $entityManager): Response
    {
        $user = new User();
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $user->setPassword(
                $userPasswordEncoder->encodePassword(
                        $user,
                        $form->get('plainPassword')->getData()
                    )
                );
            $entityManager->persist($user);
            $entityManager->flush();

            return $this->redirectToRoute('user_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('user/new.html.twig', [
            'user' => $user,
            'form' => $form->createView(),
        ]);
    }
    /**
     * @Route("/user/newC", name="user_newC", methods={"GET", "POST"})
     */
    public function newC(Request $request, EntityManagerInterface $entityManager): Response
    {
        $user = new User();
        $form = $this->createForm(UserType1::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($user);
            $entityManager->flush();

            return $this->redirectToRoute('user_showC', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('user/account.html.twig', [
            'user' => $user,
            'form' => $form->createView(),
        ]);
    }
    
    /**
     * @Route("user/{id}", name="user_show", methods={"GET"})
     */
    public function show(User $user): Response
    {
        return $this->render('user/show.html.twig', [
            'user' => $user,
        ]);
    }
    /**
     * @Route("/showC", name="user_showC", methods={"GET"})
     */
    public function showClient(UsersRepository $userRepository): Response
    {
        return $this->render('user/showinfo.html.twig', [
            'users' => $userRepository->findBy(array(),array('id'=>'DESC'),1,0)]);
    }

    /**
     * @Route("/user/{id}/edit", name="user_edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, User $user, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('user_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('user/edit.html.twig', [
            'user' => $user,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/user/{id}", name="user_delete", methods={"POST"})
     */
    public function delete(Request $request, User $user, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$user->getId(), $request->request->get('_token'))) {
            $entityManager->remove($user);
            $entityManager->flush();
        }

        return $this->redirectToRoute('user_index', [], Response::HTTP_SEE_OTHER);
    }
       /**
     * @Route("/AllUsers/json", name="AllUsers")
     */
    public function AllUsers(UsersRepository $rep,SerializerInterface $serilazer):Response
    {
        $users= $rep->findAll();

        $json= $serilazer->serialize($users,'json',['groups'=>"post:read"]);
        return new JsonResponse($json,200,[],true);
    } 
    /**
    * @Route("/AddUsers/json", name="AddUsers")
    */
   public function AddUsersJSON(Request $request,NormalizerInterface $Normalizer)
   {
       $em = $this->getDoctrine()->getManager();
       $Role = new User();
       $Role->setNomUtilisateur($request->get('nom_utilisateur'));
       $Role->setPrenomUtilisateur($request->get('prenom_utilisateur'));
       $Role->setAdresseUtilisateur($request->get('adresse_utilisateur'));
       $Role->setMailUtilisateur($request->get('mail_utilisateur'));
       $Role->setSudoUtilisateur($request->get('sudo_utilisateur'));
       $Role->setPassword($request->get('password'));
       $Role->setNumeroUtilisateur($request->get('Numero_utilisateur'));
       $em->persist($Role);
       $em->flush();

       $jsonContent= $Normalizer->normalize($Role,'json',['groups'=>"post:read"]);
       return new Response(json_encode($jsonContent));;
   }
   /**
   * @Route("/updateUserJSON/{id}", name="updateUserJSON")
*/
public function updateUserJSON ( Request $request, NormalizerInterface $Normalizer, $id)

{ 
    $em = $this->getDoctrine()->getManager(); 
    $Role = $em->getRepository(User::class)->find($id);
    $Role->setNomUtilisateur($request->get('nom_utilisateur'));
       $Role->setPrenomUtilisateur($request->get('prenom_utilisateur'));
       $Role->setMailUtilisateur($request->get('mail_utilisateur'));
    
    $em->flush();
    $jsonContent= $Normalizer->normalize($Role,'json',['groups'=>"post:read"]);
    return new Response("Information updated successfully".json_encode($jsonContent));
}

/**
* @Route("/deleteUserJSON/{id}", name="deleteUserJSON")
*/
public function deleteUserJSON(Request $request, NormalizerInterface $Normalizer, $id)
{   $em = $this->getDoctrine()->getManager(); 
    $Role = $em->getRepository (User::class)->find($id); 
    $em->remove($Role); 
    $em->flush(); 
    $jsonContent= $Normalizer->normalize($Role,'json',['groups'=>'post:read']); 
    return new Response("User deleted successfully".json_encode($jsonContent));
}

  
}

<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route; 
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Ingredient;
use App\Repository\IngredientRepository;
use App\Form\IngredientType;
use App\Service\GestionForm;
use Symfony\Component\HttpFoundation\Session\SessionInterface; 
use App\Service\Session;


class IngredientController extends AbstractController
{

     /**
     * @var IngredientRepository
     */
    private $repositoryIngredient;

    private $em;

    public function  __construct(IngredientRepository $repositoryIngredient, EntityManagerInterface $em)
    {
        $this->repositoryIngredient = $repositoryIngredient;
        $this->em = $em;
    }


    /**
     * @Route("/ingredients", name="ingredients", methods={"GET"})
     */
    public function index()
    {
        $ingredients = $this->repositoryIngredient->findAll(); 
        // Gérer les sessions
        $GestionSession = new Session($this->get('session'));
        $GestionSession->checkSession();
     
        return $this->render('ingredient/index.html.twig', array('ingredients'=>$ingredients));

    }

    /**
     * @Route("/createingredient", name="createingredient" )
     */
    public function createingredient(Request $request)
    {
        $em = $this->em;
        // Gérer les sessions 
        $GestionSession = new Session($this->get('session'));
        $GestionSession->checkSession();

        $ingredient  =  new Ingredient();
        // Methode pour retourner les erreurs liées aux champs obligatoires
        $GestionForm = new GestionForm();
        $form =  $this->createForm(IngredientType::class, $ingredient);
        $form->handleRequest($request); 

        if ($request->isMethod('POST')) { 
            // Gestion des erreurs
            $errorsForm = $GestionForm->serializeFormErrors($form);  
            $errors = $this->get('serializer')->serialize($errorsForm, 'json');
            $arrayErrors = json_decode($errors,true);
            // Si tous les champs obligatoires ont été bien remplis, on passe à la sauvegarde des données
            if(count($arrayErrors)===0){
                $champs = $form->getData();
                $em->persist($champs);
                $em->flush();
                $this->addFlash('success', 'Ingredient bien enregistrée');
                return $this->redirectToRoute('ingredients');

            } 
        }
        return $this->render('ingredient/new.html.twig', array(
            'ingredient'=>$ingredient, 
            'form' => $form->createView()
        ));

    }

    /**
     * @Route("/editingredient/{id}", name="editingredient", methods={"PUT", "GET", "POST"})
     */
    public function editIngredient(IngredientRepository $ingredientRepo, Request $request, $id)
    {
        $ingredient = $ingredientRepo->find($id);
        // Gérer les sessions
        $session = $this->get('session');
        $GestionSession = new Session($session);
        $GestionSession->checkSession();

        $form =  $this->createForm(IngredientType::class, $ingredient);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $this->em->flush();
            $this->addFlash('success', 'Ingredient bien modifié');
            return $this->redirectToRoute('ingredients');
        }
        return $this->render('ingredient/edit.html.twig', array(
            'ingredient'=>$ingredient, 
            'form' => $form->createView()
        ));

    }
    
    /**
     * @Route("/ingredient/{id}", name="deleteingredient", methods={"DELETE"})
     */
    public function deleteIngredient(IngredientRepository $ingredientRepo,$id)
    {
        $ingredient = $ingredientRepo->find($id);
        if($ingredient){
            $this->em->remove($ingredient);
            $this->em->flush();
            $this->addFlash('success', 'Ingredient bien supprimé');
        }

        $ingredients = $this->repositoryIngredient->findAll(); 
        // Transform objects to json
        $data = $this->get('serializer')->serialize($ingredients, 'json',[
            'circular_reference_handler' => function ($object) {
                return $object->getId();
            }
        ] );
        $response = new Response($data);  
        $response->headers->set('Content-Type', 'application/json');

        return $response;

    } 

}

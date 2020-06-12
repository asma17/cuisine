<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route; 
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Recette;
use App\Entity\Ingredient;
use App\Entity\IngredientRecette;
use App\Entity\Unite;
use App\Repository\RecetteRepository; 
use App\Repository\IngredientRecetteRepository;
use App\Form\IngredientRecetteType;
use App\Form\IngredientType;
use App\Form\RecetteType;
use App\Form\UniteType;
use App\Service\GestionForm;
use App\Service\Session;


class RecetteController extends AbstractController
{

    /**
     * @var RecetteRepository
    */
    private $repositoryRecette;
    private $em;
    private $repositoryIngredientRecette;
    public function  __construct(RecetteRepository $repositoryRecette, IngredientRecetteRepository $repositoryIngredientRecette, EntityManagerInterface $em)
    {
        $this->repositoryRecette = $repositoryRecette;
        $this->repositoryIngredientRecette = $repositoryIngredientRecette;
        $this->em = $em;
    }

    /**
     * @Route("/recette", name="recette", methods={"GET"})
     */
    public function index()
    {
        $recettes = $this->repositoryRecette->findAll(); 
        $session = $this->get('session'); 
        $GestionSession = new Session($session);
        $GestionSession->checkSession();

        return $this->render('recette/index.html.twig', array('recettes'=>$recettes));
    }

    /**
     * @Route("/createrecette", name="createrecette" )
     */
    public function newRecette(Request $request)
    {
        $em = $this->em;
        $check = "No";
        $session = $this->get('session'); 
        $GestionSession = new Session($session);
        $GestionSession->checkSession();
        $tabIngredients = $session->get('ingredients');
        
        $recette  =  new Recette();
        // Methode pour retourner les erreurs liées aux champs obligatoires
        $GestionForm = new GestionForm();
        $form =  $this->createForm(RecetteType::class, $recette);
        $form->handleRequest($request); 

        $ingredient = new Ingredient();
        $formIngredient =  $this->createForm(IngredientType::class, $ingredient);

        $unite = new Unite();
        $formUnite =  $this->createForm(UniteType::class, $unite);

        $ingredientRecette = new IngredientRecette();
        $formIngredientRecette =  $this->createForm(IngredientRecetteType::class, $ingredientRecette);
        $formIngredientRecette->handleRequest($request); 

        if ($request->isMethod('POST')) { 
            // Gestion des erreurs
            $errorsForm = $GestionForm->serializeFormErrors($form);  
            $errors = $this->get('serializer')->serialize($errorsForm, 'json');
            $arrayErrors = json_decode($errors,true); 
            // Si tous les champs obligatoires ont été bien remplis pour le formulaire recette, on passe à la sauvegarde des données
            if(count($arrayErrors)===0 ){
                // Vérifier si tous les champs obligatoires ont été bien remplis pour le formulaire ingrédient
                $check = $this->repositoryRecette->checkBlank($_POST);
                if($check == "No"){
                    $champs = $form->getData(); 
                    $recette = $this->repositoryRecette->saveRecette($_POST, $recette);
                    $em->persist($champs);
                    $em->flush();
                    $session->remove('ingredients'); 
                    $this->addFlash('success', 'Recette bien enregistrée');
                    return $this->redirectToRoute('recette');
                }  
            }  
        }

        return $this->render('recette/new.html.twig', array(
            'recette'=>$recette,
            'form_ingredient'=>$formIngredient->createView(), 
            'form_ingredient_recette'=>$formIngredientRecette->createView(), 
            'form_unite'=>$formUnite->createView(), 
            'form_recette' => $form->createView(),
            'tabIngredients' => $tabIngredients,
            'check' =>$check
        ));

    }

    /**
     * @Route("/editrecette/{id}", name="editrecette", methods={"PUT", "GET", "POST"})
     */
    public function editRecette(RecetteRepository $recetteRepo, Request $request, $id)
    {
        $check= "No";
        $em = $this->em;
        $recette = $recetteRepo->find($id);
        $session = $this->get('session');
        // Gérer les sessions
        $GestionSession = new Session($session);
        $GestionSession->checkSession();
        
        if ( $session->has('ingredients')){
            $tabIngredients = $session->get('ingredients');
        } else {
            $tabIngredients = $this->repositoryIngredientRecette->findIngredientRecette($recette->getId());
            $session->set('ingredients', $tabIngredients);
        }

        $form =  $this->createForm(RecetteType::class, $recette);
        $form->handleRequest($request);
        // Methode pour retourner les erreurs liées aux champs obligatoires
        $GestionForm = new GestionForm();

        $ingredient = new Ingredient();
        $formIngredient =  $this->createForm(IngredientType::class, $ingredient);

        $unite = new Unite();
        $formUnite =  $this->createForm(UniteType::class, $unite);

        $ingredientRecette = new IngredientRecette();
        $formIngredientRecette =  $this->createForm(IngredientRecetteType::class, $ingredientRecette);

        if($form->isSubmitted() && $form->isValid()){
            // Gestion des erreurs
            $errorsForm = $GestionForm->serializeFormErrors($form);  
            $errors = $this->get('serializer')->serialize($errorsForm, 'json');
            $arrayErrors = json_decode($errors,true); 
            // Si tous les champs obligatoires ont été bien remplis, on passe à la sauvegarde des données
            if(count($arrayErrors)===0){
                // Vérifier si tous les champs obligatoires ont été bien remplis pour le formulaire ingrédient
                $check = $this->repositoryRecette->checkBlank($_POST);
                if($check == "No"){
                    $champs = $form->getData(); 
                    $this->repositoryIngredientRecette->removeIngredient($recette);
                    $recette = $this->repositoryRecette->saveRecette($_POST, $recette);
                    $em->persist($champs);
                    $em->flush();
                    $session->remove('ingredients'); 
                    $this->addFlash('success', 'Recette bien enregistrée');
                    return $this->redirectToRoute('recette');
                }
            }
        }

        return $this->render('recette/edit.html.twig', array(
            'recette'=>$recette,
            'form_ingredient'=>$formIngredient->createView(), 
            'form_ingredient_recette'=>$formIngredientRecette->createView(), 
            'form_unite'=>$formUnite->createView(), 
            'form_recette' => $form->createView(),
            'tabIngredients' => $tabIngredients,
            'check' =>$check
        ));

    }
    
    /**
     * @Route("/recette/{id}", name="deleterecette", methods={"DELETE"})
     */
    public function deleteRecette(RecetteRepository $recetteRepo,$id)
    {
        $recette = $recetteRepo->find($id);
        if($recette){
            $this->em->remove($recette);
            $this->em->flush();
            $this->addFlash('success', 'Recette bien supprimée');
        }

        $recettes = $this->repositoryRecette->findAll(); 
        // Transform objects to json
        $data = $this->get('serializer')->serialize($recettes, 'json',[
            'circular_reference_handler' => function ($object) {
                return $object->getId();
            }
        ] );
        
        $response = new Response($data);  
        $response->headers->set('Content-Type', 'application/json');

        return $response;
    } 

}
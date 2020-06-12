<?php

namespace App\Repository;

use App\Entity\Recette;
use App\Entity\IngredientRecette;
use App\Repository\IngredientRepository;
use App\Repository\UniteRepository;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\ORM\EntityManagerInterface;


/**
 * @method Recette|null find($id, $lockMode = null, $lockVersion = null)
 * @method Recette|null findOneBy(array $criteria, array $orderBy = null)
 * @method Recette[]    findAll()
 * @method Recette[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
*/
class RecetteRepository extends ServiceEntityRepository
{
    private $em;
    private $IngredientRepository;
    private $UniteRepository;

    public function __construct(ManagerRegistry $registry, EntityManagerInterface $em, IngredientRepository $IngredientRepository, UniteRepository $UniteRepository  )
    {
        parent::__construct($registry, Recette::class);
        $this->em = $em ;
        $this->IngredientRepository = $IngredientRepository;
        $this->UniteRepository = $UniteRepository;
    }

    // Sauvegarder la recette
    public function saveRecette($data,$recette){
        foreach($data as $key=>$value){
            if (strpos($key, 'ingredient') !== false) {
                $RecetteIngredient = new IngredientRecette(); 
                $RecetteIngredient->setRecette($recette) ; 
                    foreach($value as $index=>$valeur){
                        if($index== "nom") { 
                            $ingredient = $this->IngredientRepository->findOneBy(['id'=>$valeur]); 
                            $RecetteIngredient->setIngredient($ingredient) ; 
                        }
                        if($index== "qte") { 
                        $valeur = str_replace(",", ".", $valeur);
                        $RecetteIngredient->setQuantite($valeur) ; 
                        }
                        if($index== "libelle") { 
                            $unite = $this->UniteRepository->findOneBy(['id'=>$valeur]); 
                            $RecetteIngredient->setUnite($unite) ; 
                        }
                    }
                    $this->em->persist($RecetteIngredient);
                    $this->em->flush(); 
            }
        }
    }

    // check if empty value exist for ingredients
    public function checkBlank($data){
        $check= "No";
        foreach($data as $key=>$value){
            if (strpos($key, 'ingredient') !== false) {
                    foreach($value as $index=>$valeur){
                        if($valeur== "" || $valeur== 'Choisir') { 
                            $check = "Yes";
                            break;
                        } 
                    } 
            }
        }
        return $check;
    }    
   
}

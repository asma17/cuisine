<?php

namespace App\Repository;

use App\Entity\IngredientRecette;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\ORM\EntityManagerInterface;

/**
 * @method IngredientRecette|null find($id, $lockMode = null, $lockVersion = null)
 * @method IngredientRecette|null findOneBy(array $criteria, array $orderBy = null)
 * @method IngredientRecette[]    findAll()
 * @method IngredientRecette[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class IngredientRecetteRepository extends ServiceEntityRepository
{
    protected $em;
    public function __construct(ManagerRegistry $registry, EntityManagerInterface $em)
    {
        parent::__construct($registry, IngredientRecette::class);
        $this->em = $em ;
    }

    public function findIngredientRecette($recette)
    {
        $query =  $this->createQueryBuilder('i')
            ->andWhere('i.recette = :val')
            ->setParameter('val', $recette)
            ->orderBy('i.id', 'ASC')
            ->getQuery()
            ->getResult()
        ;
        $tab = [];
        for($i=0; $i<count($query); $i++){ 
            $myTab['nom'] = $query[$i]->getIngredient()->getId(); // id ingrédient
            $myTab['qte'] = $query[$i]->getquantite();
            $myTab['libelle'] = $query[$i]->getUnite()->getId(); //unité
            array_push($tab, $myTab);
        }

        return $tab;
    }
 
    // Remove Ingrédient
    public function removeIngredient($recette){
        $RecetteIngredient =  $this->findBy(['recette'=>$recette]); 
        foreach($RecetteIngredient as  $value){
            $define = $this->findOneBy(['id'=>$value->getId()]); 
            if($define){
                $this->em->remove($define);
                $this->em->flush(); 
            }
            
        }
   }
}

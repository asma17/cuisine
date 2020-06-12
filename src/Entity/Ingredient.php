<?php

namespace App\Entity;

use App\Repository\IngredientRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * @ORM\Entity(repositoryClass=IngredientRepository::class)
 * @UniqueEntity("nom", message = "Cette valeur existe déjà"))
 */
class Ingredient
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255, nullable=false)
     * @Assert\NotNull(message = "Ce champ ne peut pas être vide")
     * @Assert\NotBlank(message = "Ce champ ne peut pas être vide")
     * @Assert\Length(
     *      min = 2,
     *      max = 200,
     *      minMessage = "le nom de l'ingrédient doit comporter au moins {{ limit }} caractères",
     *      maxMessage = "Le nom de l'ingrédient doit comporter au max {{ limit }} caractères",
     * )
     */
    private $nom;
 
    /**
        * @ORM\OneToMany(targetEntity="App\Entity\IngredientRecette", mappedBy="ingredient", cascade={"persist", "remove", "all"}, orphanRemoval=true)
    */
    private $ingredientRecette;

    public function __construct()
    {
        $this->ingredientRecette = new ArrayCollection();
    }


    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(?string $nom): self
    {
        $this->nom = $nom;

        return $this;
    }

    /**
     * @return Collection|IngredientRecette[]
     */
    public function getIngredientRecette(): Collection
    {
        return $this->ingredientRecette;
    }

    public function addIngredientRecette(IngredientRecette $ingredientRecette): self
    {
        if (!$this->ingredientRecette->contains($ingredientRecette)) {
            $this->ingredientRecette[] = $ingredientRecette;
            $ingredientRecette->setIngredient($this);
        }

        return $this;
    }

    public function removeIngredientRecette(IngredientRecette $ingredientRecette): self
    {
        if ($this->ingredientRecette->contains($ingredientRecette)) {
            $this->ingredientRecette->removeElement($ingredientRecette);
            // set the owning side to null (unless already changed)
            if ($ingredientRecette->getIngredient() === $this) {
                $ingredientRecette->setIngredient(null);
            }
        }

        return $this;
    }
    
    public function __toString(){
        return $this->nom; 
    }

}

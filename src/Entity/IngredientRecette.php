<?php

namespace App\Entity;

use App\Repository\IngredientRecetteRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;


/**
 * @ORM\Entity(repositoryClass=IngredientRecetteRepository::class)
 */
class IngredientRecette
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="float")
     * @Assert\NotNull(message = "Ce champ ne peut pas être vide")
     */
    private $quantite;
  
    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Recette", inversedBy="ingredientrecette", cascade={"persist"})
     * @ORM\JoinColumn(name="recette_id", referencedColumnName="id", nullable=false)
    */
    private $recette;

    /**
    * @ORM\ManyToOne(targetEntity="App\Entity\Ingredient", inversedBy="ingredientRecette", cascade={"persist"})
    * @ORM\JoinColumn(name="ingredient_id", referencedColumnName="id", nullable=false)
    */
    private $ingredient;

        /**
    * @ORM\ManyToOne(targetEntity="App\Entity\Unite", inversedBy="ingredientRecette", cascade={"persist"})
    * @ORM\JoinColumn(name="unite_id", referencedColumnName="id", nullable=false)
    */
    private $unite;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getQuantite(): ?float
    {
        return $this->quantite;
    }

    public function setQuantite(?float $quantite): self
    {
        $this->quantite = $quantite;

        return $this;
    }

    public function getRecette(): ?Recette
    {
        return $this->recette;
    }

    public function setRecette(?Recette $recette): self
    {
        $this->recette = $recette;

        return $this;
    }

    public function getIngredient(): ?Ingredient
    {
        return $this->ingredient;
    }

    public function setIngredient(?Ingredient $ingredient): self
    {
        $this->ingredient = $ingredient;

        return $this;
    }

    public function getUnite(): ?Unite
    {
        return $this->unite;
    }

    public function setUnite(?Unite $unite): self
    {
        $this->unite = $unite;

        return $this;
    }
}

<?php

namespace App\Entity;

use App\Repository\RecetteRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=RecetteRepository::class)
 */
class Recette
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Assert\NotNull(message = "Ce champ ne peut pas être vide")
     */
    private $titre;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $soustitre;

     /**
     * @ORM\OneToMany(targetEntity="App\Entity\IngredientRecette", mappedBy="recette", cascade={"persist", "remove", "all"}, orphanRemoval=true)
     * @ORM\JoinColumn(name="recette_id", referencedColumnName="id", nullable=false)
     */
    private $ingredientrecette;  
  
    public $foringredient;

    public function __construct()
    {
        $this->ingredientrecette = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitre(): ?string
    {
        return $this->titre;
    }

    public function setTitre(?string $titre): self
    {
        $this->titre = $titre;

        return $this;
    }

    public function getSoustitre(): ?string
    {
        return $this->soustitre;
    }

    public function setSoustitre(?string $soustitre): self
    {
        $this->soustitre = $soustitre;

        return $this;
    }

    /**
     * @return Collection|IngredientRecette[]
     */
    public function getIngredientrecette(): Collection
    {
        return $this->ingredientrecette;
    }

    public function addIngredientrecette(IngredientRecette $ingredientrecette): self
    {
        if (!$this->ingredientrecette->contains($ingredientrecette)) {
            $this->ingredientrecette[] = $ingredientrecette;
            $ingredientrecette->setRecette($this);
        }

        return $this;
    }

    public function removeIngredientrecette(IngredientRecette $ingredientrecette): self
    {
        if ($this->ingredientrecette->contains($ingredientrecette)) {
            $this->ingredientrecette->removeElement($ingredientrecette);
            // set the owning side to null (unless already changed)
            if ($ingredientrecette->getRecette() === $this) {
                $ingredientrecette->setRecette(null);
            }
        }

        return $this;
    }
}

<?php

namespace App\Entity;

use App\Repository\UniteRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=UniteRepository::class)
 */
class Unite
{ 
    const PIECE = "Piece";
    const GRAMME = "Gramme";
    const KILOGRAMME = "Kg";
    const MILLILITRE = "Millilitre";
    const LITRE = "Litre";

    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $libelle;
    
    /**
     * @ORM\OneToMany(targetEntity="App\Entity\IngredientRecette", mappedBy="unite", cascade={"persist", "remove", "all"}, orphanRemoval=true)
     * @ORM\JoinColumn(name="unite_id", referencedColumnName="id", nullable=false)
    */
    private $ingredientrecette;

    public function __construct()
    {
        $this->ingredientrecette = new ArrayCollection();
    } 

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getLibelle(): ?string
    {
        return $this->libelle;
    }

    public function setLibelle(?string $libelle): self
    {
        $this->libelle = $libelle;

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
            $ingredientrecette->setUnite($this);
        }

        return $this;
    }

    public function removeIngredientrecette(IngredientRecette $ingredientrecette): self
    {
        if ($this->ingredientrecette->contains($ingredientrecette)) {
            $this->ingredientrecette->removeElement($ingredientrecette);
            // set the owning side to null (unless already changed)
            if ($ingredientrecette->getUnite() === $this) {
                $ingredientrecette->setUnite(null);
            }
        }

        return $this;
    }

    public static function getListUnite() {
        return array(
                    self::PIECE, 
                    self::GRAMME, 
                    self::KILOGRAMME,
                    self::MILLILITRE,
                    self::LITRE,
                    );
      }
}
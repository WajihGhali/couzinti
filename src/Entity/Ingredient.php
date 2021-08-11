<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\IngredientRepository")
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
     * @ORM\Column(type="string", length=255)
     */
    private $nameIng;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Recipe", inversedBy="ingredient")
     * @ORM\JoinColumn(name="recipe_id",referencedColumnName="id")
     */
    private $recipe;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNameIng(): ?string
    {
        return $this->nameIng;
    }

    public function setNameIng(string $nameIng): self
    {
        $this->nameIng = $nameIng;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getRecipe()
    {
        return $this->recipe;
    }

    /**
     * @param mixed $recipe
     * @return Ingredient
     */
    public function setRecipe($recipe)
    {
        $this->recipe = $recipe;
        return $this;
    }




}

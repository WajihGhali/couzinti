<?php

namespace App\Entity;



class IngredientSearch
{

    /**
     * @var string|null
     */
    private $ingredientSearch;

    /**
     * @return string|null
     */
    public function getIngredientSearch(): ?string
    {
        return $this->ingredientSearch;
    }

    /**
     * @param string|null $ingredientSearch
     * @return IngredientSearch
     */
    public function setIngredientSearch(?string $ingredientSearch): IngredientSearch
    {
        $this->ingredientSearch = $ingredientSearch;
        return $this;
    }







}

<?php

namespace App\Entity;



class RecipeSearch
{

    /**
     * @var string|null
     */
    private $recipeName;



    /**
     * @return string|null
     */
    public function getRecipeName(): ?string
    {
        return $this->recipeName;
    }

    /**
     * @param string|null $recipeName
     * @return RecipeSearch
     */
    public function setRecipeName(?string $recipeName): RecipeSearch
    {
        $this->recipeName = $recipeName;
        return $this;
    }


}

<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\RankRepository")
 */
class Rank
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="integer", nullable=true)
     * * @Assert\Range(min=0,max=5)
     */
    private $mark;


    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Recipe",inversedBy="recipeRank")
     * @ORM\JoinColumn()
     */
    private $rankRecipe;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User",inversedBy="userRank")
     * @ORM\JoinColumn()
     */
    private $rankUser;



    public function getId(): ?int
    {
        return $this->id;
    }

    public function getMark(): ?int
    {
        return $this->mark;
    }

    public function setMark(?int $mark): self
    {
        $this->mark = $mark;

        return $this;
    }


    /**
     * @return mixed
     */
    public function getRankRecipe()
    {
        return $this->rankRecipe;
    }

    /**
     * @param mixed $rankRecipe
     * @return Rank
     */
    public function setRankRecipe($rankRecipe)
    {
        $this->rankRecipe = $rankRecipe;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getRankUser()
    {
        return $this->rankUser;
    }

    /**
     * @param mixed $rankUser
     * @return Rank
     */
    public function setRankUser($rankUser)
    {
        $this->rankUser = $rankUser;
        return $this;
    }


}

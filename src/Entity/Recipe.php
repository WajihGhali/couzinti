<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\Collection;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Validator\Constraints as Assert;
use Vich\UploaderBundle\Mapping\Annotation as Vich;

/**
 * @ORM\Entity(repositoryClass="App\Repository\RecipeRepository")
 * @Vich\Uploadable()
 * @ORM\HasLifecycleCallbacks()
 */
class Recipe
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @var string
     * @ORM\Column(type="string",length=255, nullable=true)
     */
    private $filename;

    /**
     * @var File
     * @Vich\UploadableField(mapping="recipe_image", fileNameProperty="filename")
     */
    private $imageFile;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\Length(min=2,minMessage="Au moin 2 caracteres")
     */
    private $name;

    /**
     * @ORM\Column(type="text")
     * @Assert\NotBlank()
     */
    private $Preparation;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User",inversedBy="recipes")
     * @ORM\JoinColumn()
     */
    private $user;

    /**
     * @ORM\Column(type="datetime")
     */
    private $time;

    /**
     * @var Collection
     * @ORM\ManyToMany(targetEntity="App\Entity\User", inversedBy="recipesLiked")
     * @ORM\JoinTable(name="recipe_likes",
     *     joinColumns={@ORM\JoinColumn(name="recipe_id",referencedColumnName="id")},
     *     inverseJoinColumns={@ORM\JoinColumn(name="user_id",referencedColumnName="id")}
     *     )
     */
    private $likedBy;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Rank",mappedBy="rankRecipe", cascade={"persist"}, orphanRemoval=true)
     */
    private $recipeRank;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Ingredient", mappedBy="recipe", cascade={"persist"}, orphanRemoval=true)
     */
    private $ingredient;


    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Comment",mappedBy="commentRecipe", cascade={"persist"}, orphanRemoval=true)
     */
    private $recipeComment;


    /**
     * @ORM\Column(type="datetime")
     */
    private $updated_at;


    public function __construct()
    {
        $this->likedBy =new ArrayCollection();
        $this->recipeRank = new ArrayCollection();
        $this->ingredient = new ArrayCollection();
        $this->recipeComment = new ArrayCollection();
    }


    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getPreparation(): ?string
    {
        return $this->Preparation;
    }

    public function setPreparation(string $Preparation): self
    {
        $this->Preparation = $Preparation;

        return $this;
    }

    /**
     * @return User
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * @param mixed $user
     */
    public function setUser($user): void
    {
        $this->user = $user;
    }

    /**
     * @return mixed
     */
    public function getTime()
    {
        return $this->time;
    }

    /**
     * @param mixed $time
     * @return Recipe
     */
    public function setTime($time)
    {
        $this->time = $time;
        return $this;
    }

    /**
     * @ORM\PrePersist()
     */
    public function setTimeOnPersist():void
    {
        $this->time=new \DateTime();
    }

    /**
     * @return Collection
     */
    public function getLikedBy(): Collection
    {
        return $this->likedBy;
    }

    public function like(User $user)
    {
        if ($this->likedBy->contains($user))
        {
            return;
        }
        $this->likedBy->add($user);
    }

    /**
     * @return mixed
     */
    public function getRecipeRank()
    {
        return $this->recipeRank;
    }

    /**
     * @param mixed $recipeRank
     * @return Recipe
     */
    public function setRecipeRank($recipeRank)
    {
        $this->recipeRank = $recipeRank;
        return $this;
    }

    public function addLikedBy(User $likedBy): self
    {
        if (!$this->likedBy->contains($likedBy)) {
            $this->likedBy[] = $likedBy;
        }

        return $this;
    }

    public function removeLikedBy(User $likedBy): self
    {
        if ($this->likedBy->contains($likedBy)) {
            $this->likedBy->removeElement($likedBy);
        }

        return $this;
    }

    public function addRecipeRank(Rank $recipeRank): self
    {
        if (!$this->recipeRank->contains($recipeRank)) {
            $this->recipeRank[] = $recipeRank;
            $recipeRank->setRankRecipe($this);
        }

        return $this;
    }

    public function removeRecipeRank(Rank $recipeRank): self
    {
        if ($this->recipeRank->contains($recipeRank)) {
            $this->recipeRank->removeElement($recipeRank);
            // set the owning side to null (unless already changed)
            if ($recipeRank->getRankRecipe() === $this) {
                $recipeRank->setRankRecipe(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Ingredient[]
     */
    public function getIngredient(): Collection
    {
        return $this->ingredient;
    }

    public function addIngredient(Ingredient $ingredient): self
    {
        if (!$this->ingredient->contains($ingredient)) {
            $this->ingredient[] = $ingredient;
            $ingredient->setRecipe($this);
        }

        return $this;
    }

    public function removeIngredient(Ingredient $ingredient): self
    {
        if ($this->ingredient->contains($ingredient)) {
            $this->ingredient->removeElement($ingredient);
            // set the owning side to null (unless already changed)
            if ($ingredient->getRecipe() === $this) {
                $ingredient->setRecipe(null);
            }
        }

        return $this;
    }


    /**
     * @return string|null
     */
    public function getFilename(): ?string
    {
        return $this->filename;
    }

    /**
     * @param string|null $filename
     * @return Recipe
     */
    public function setFilename(?string $filename): Recipe
    {
        $this->filename = $filename;
        return $this;
    }

    /**
     * @return File|null
     */
    public function getImageFile(): ?File
    {
        return $this->imageFile;
    }

    /**
     * @param File|null $imageFile
     * @return Recipe
     * @throws \Exception
     */
    public function setImageFile(?File $imageFile): Recipe
    {
        $this->imageFile = $imageFile;
        if ($this->imageFile instanceof UploadedFile) {
            $this->updated_at = new \DateTime('now');
        }
        return $this;
    }

    public function getUpdatedAt(): ?\DateTimeInterface
    {
        return $this->updated_at;
    }

    public function setUpdatedAt(\DateTimeInterface $updated_at): self
    {
        $this->updated_at = $updated_at;

        return $this;
    }

    /**
     * @return Collection
     */
    public function getRecipeComment(): Collection
    {
        return $this->recipeComment;
    }

    /**
     * @param ArrayCollection $recipeComment
     * @return Recipe
     */
    public function setRecipeComment(ArrayCollection $recipeComment): Recipe
    {
        $this->recipeComment = $recipeComment;
        return $this;
    }





}

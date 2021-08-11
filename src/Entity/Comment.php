<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\CommentRepository")
 * @ORM\HasLifecycleCallbacks()
 */
class Comment
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $content;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User",inversedBy="userComment")
     * @ORM\JoinColumn()
     */
    private $commentUser;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Recipe",inversedBy="recipeComment")
     * @ORM\JoinColumn()
     */
    private $commentRecipe;

    /**
     * @ORM\Column(type="datetime",nullable=true)
     */
    private $time;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getContent(): ?string
    {
        return $this->content;
    }

    public function setContent(?string $content): self
    {
        $this->content = $content;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getCommentUser()
    {
        return $this->commentUser;
    }

    /**
     * @param mixed $commentUser
     * @return Comment
     */
    public function setCommentUser($commentUser)
    {
        $this->commentUser = $commentUser;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getCommentRecipe()
    {
        return $this->commentRecipe;
    }

    /**
     * @param mixed $commentRecipe
     * @return Comment
     */
    public function setCommentRecipe($commentRecipe)
    {
        $this->commentRecipe = $commentRecipe;
        return $this;
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
     * @return Comment
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


}

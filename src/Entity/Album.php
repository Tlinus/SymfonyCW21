<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\AlbumRepository")
 */
class Album
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @Assert\NotBlank()
     * @Assert\Length(
     *      min=1,
     *      max=50
     * )
     * @Assert\Regex(
     *      pattern="/^[A-z0-9À-ÿ\s-]+$/"
     * )
     * @ORM\Column(type="string", length=50)
     */
    private $name;

    /**
     * @Assert\NotBlank()
     *  @Assert\Length(
     *      min=1,
     *      max=50
     * )
     * @Assert\Regex(
     *      pattern="/^[A-z0-9À-ÿ\s-]+$/"
     * )
     * @ORM\Column(type="text")
     */
    private $description;

    /**
     *  @Assert\Range(
     *      min = 1,
     *      max = 100,
     * )
     * @ORM\Column(type="float")
     */
    private $price;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Category", inversedBy="albums")
     * @ORM\JoinColumn(nullable=false)
     */
    private $category;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="albums")
     */
    private $user;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $state;

    public function __construct()
    {
      $this->state = "create";
    }


    public function __toString(): ?string
    {
        return $this->name;
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

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getPrice(): ?float
    {
        return $this->price;
    }

    public function setPrice(float $price): self
    {
        $this->price = $price;

        return $this;
    }

    public function getCategory(): ?Category
    {
        return $this->category;
    }

    public function setCategory(?Category $category): self
    {
        $this->category = $category;

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

        return $this;
    }

    public function getState(): ?string
    {
        return $this->state;
    }

    public function setState(string $state): self
    {
        $this->state = $state;

        return $this;
    }
}

<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Patch;
use ApiPlatform\Metadata\Post;
use App\Repository\RatingRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: RatingRepository::class)]
#[ApiResource(
    operations: [
        new Get(),
        new GetCollection(),
        new Post(),
        new Patch()
    ],
    normalizationContext: ['groups' => ['rating:read']],
    denormalizationContext: ['groups' => ['rating:write']]
)]
class Rating
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    #[Groups(['rating:read', 'rating:write'])]
    private ?float $Value = null;

    #[ORM\Column(length: 255)]
    #[Groups(['rating:read', 'rating:write'])]
    private ?string $Author = null;

    #[ORM\Column(length: 255)]
    #[Groups(['rating:read'])]
    private ?string $Recipe = null;

    #[ORM\ManyToOne(targetEntity: User::class, inversedBy: "ratings")]
    private ?User $author = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getValue(): ?float
    {
        return $this->Value;
    }

    public function setValue(float $Value): static
    {
        $this->Value = $Value;

        return $this;
    }

    public function getAuthor(): ?string
    {
        return $this->Author;
    }

    public function setAuthor(string $Author): static
    {
        $this->Author = $Author;

        return $this;
    }

    public function getRecipe(): ?string
    {
        return $this->Recipe;
    }

    public function setRecipe(string $Recipe): static
    {
        $this->Recipe = $Recipe;

        return $this;
    }
}

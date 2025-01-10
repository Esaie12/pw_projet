<?php

namespace App\Entity;

use App\Repository\RatingRepository;
use Doctrine\ORM\Mapping as ORM;
use App\Entity\JobPosting;
use App\Entity\Developer;
use App\Entity\User;

#[ORM\Entity(repositoryClass: RatingRepository::class)]
class Rating
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;


    #[ORM\Column(type: 'integer')]
    private int $rating; // Note entre 1 et 5

    #[ORM\Column(type: 'text', nullable: true)]
    private ?string $review = null; 

    #[ORM\ManyToOne(targetEntity: User::class)]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $ratedDev = null; 

    #[ORM\ManyToOne(targetEntity: User::class)]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $ratedBy = null;

    #[ORM\Column(type: 'datetime')]
    private \DateTime $createdAt;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getRating(): int
    {
        return $this->rating;
    }

    public function setRating(int $rating): self
    {
        if ($rating < 1 || $rating > 5) {
            throw new \InvalidArgumentException('La note doit être comprise entre 1 et 5.');
        }
        $this->rating = $rating;
        return $this;
    }

    public function getReview(): ?string
    {
        return $this->review;
    }

    public function setReview(?string $review): self
    {
        $this->review = $review;
        return $this;
    }

    public function getRatedDev(): ?User
    {
        return $this->ratedDev;
    }

    public function setRatedDev(User $ratedDev): self
    {
        $this->ratedDev = $ratedDev;
        return $this;
    }

    public function getRatedBy(): ?User
    {
        return $this->ratedBy;
    }

    public function setRatedBy(User $ratedBy): self
    {
        $this->ratedBy = $ratedBy;
        return $this;
    }

    public function getCreatedAt(): \DateTime
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTime $createdAt): self
    {
        $this->createdAt = $createdAt;
        return $this;
    }
}

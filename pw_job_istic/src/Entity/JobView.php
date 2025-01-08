<?php

namespace App\Entity;

use App\Repository\JobViewRepository;
use Doctrine\ORM\Mapping as ORM;
use App\Entity\JobPosting;
use App\Entity\Developer;

#[ORM\Entity(repositoryClass: JobViewRepository::class)]
class JobView
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(targetEntity: JobPosting::class, inversedBy: 'views')]
    #[ORM\JoinColumn(nullable: false)]
    private ?JobPosting $job = null;

    #[ORM\ManyToOne(targetEntity: Developer::class)]
    #[ORM\JoinColumn(nullable: false)]
    private ?Developer $developer = null;

    #[ORM\Column(type: 'datetime')]
    private ?\DateTimeInterface $viewedAt = null;

    public function __construct()
    {
        $this->viewedAt = new \DateTime(); // Initialise à la date/heure actuelle
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getJob(): ?JobPosting
    {
        return $this->job;
    }

    public function setJob(JobPosting $job): self
    {
        $this->job = $job;
        return $this;
    }

    public function getDeveloper(): ?Developer
    {
        return $this->developer;
    }

    public function setDeveloper(Developer $developer): self
    {
        $this->developer = $developer;
        return $this;
    }

    public function getViewedAt(): ?\DateTimeInterface
    {
        return $this->viewedAt;
    }
}
